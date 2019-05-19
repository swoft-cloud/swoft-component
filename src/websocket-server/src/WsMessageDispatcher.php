<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use ReflectionException;
use ReflectionType;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Context\Context;
use Swoft\Http\Message\Request;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Exception\WsMessageException;
use Swoft\WebSocket\Server\Exception\WsMessageParseException;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\Message\Message;
use Swoft\WebSocket\Server\Message\Response;
use Swoft\WebSocket\Server\MessageParser\RawTextParser;
use Swoft\WebSocket\Server\Router\Router;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Throwable;

/**
 * Class WsMessageDispatcher
 *
 * @since 2.0
 *
 * @Bean("wsMsgDispatcher")
 */
class WsMessageDispatcher
{
    /**
     * Dispatch ws message handle
     *
     * @param Server $server
     * @param Frame  $frame
     *
     * @throws Throwable
     */
    public function dispatch(Server $server, Frame $frame): void
    {
        $fd = $frame->fd;
        /** @var Connection $conn */
        $conn = Session::mustGet();
        $info = $conn->getModuleInfo();

        // Want custom message handle, will don't trigger message parse and dispatch.
        if ($method = $info['eventMethods']['message'] ?? '') {
            $class = $info['class'];
            \server()->log("Message: conn#{$fd} call custom message handler '{$class}::{$method}'", [], 'debug');

            /** @var WsModuleInterface $module */
            $module = BeanFactory::getSingleton($class);
            $module->$method($server, $frame);
            return;
        }

        // Use swoft message dispatcher
        $parseClass = $info['messageParser'] ?? RawTextParser::class;

        /** @var MessageParserInterface $parser */
        $parser = BeanFactory::getSingleton($parseClass);
        if (!$parser) {
            throw new WsMessageException("message parser bean '$parseClass' is not exists");
        }

        try {
            $msg = $parser->decode($frame->data);
        } catch (Throwable $e) {
            throw new WsMessageParseException("parse message error '{$e->getMessage()}", 500, $e);
        }

        // Set parser to context
        Context::mustGet()->setParser($parser);

        $data  = $msg->getData();
        $cmdId = $msg->getCmd() ?: $info['defaultCommand'];

        /** @var Router $router */
        $router = Swoft::getBean('wsRouter');

        [$status, $handler] = $router->matchCommand($info['path'], $cmdId);
        if ($status === Router::NOT_FOUND) {
            throw new WsMessageRouteException("message command '$cmdId' is not found, in module {$info['path']}");
        }

        [$ctlClass, $ctlMethod] = $handler;

        \server()->log("Message: conn#{$fd} call message command handler '{$ctlClass}::{$ctlMethod}'", $msg->toArray(), 'debug');

        $object = BeanFactory::getBean($ctlClass);
        $params = $this->getBindParams($ctlClass, $ctlMethod, $frame, $data);
        $result = $object->$ctlMethod(...$params);

        // If result is not null, encode and replay
        if ($result instanceof Response) {
            $result->send();
        } elseif ($result instanceof Message) {
            $server->push($fd, $parser->encode($result));
        } elseif ($result !== null) {
            $server->push($fd, $parser->encode(Message::new($cmdId, $result)));
        }
    }

    /**
     * Get method bounded params
     *
     * @param string $class
     * @param string $method
     * @param Frame  $frame
     * @param mixed  $data
     *
     * @return array
     * @throws ReflectionException
     */
    private function getBindParams(string $class, string $method, Frame $frame, $data): array
    {
        $classInfo = Swoft::getReflection($class);
        if (!isset($classInfo['methods'][$method])) {
            return [];
        }

        // binding params
        $bindParams   = [];
        $methodParams = $classInfo['methods'][$method]['params'];

        /**
         * @var string         $name
         * @var ReflectionType $paramType
         * @var mixed          $devVal
         */
        foreach ($methodParams as [$name, $paramType, $devVal]) {
            // Defined type of the param
            $type = $paramType ? $paramType->getName() : '';

            if ($type === '' && $name === 'data') {
                $bindParams[] = $data;
            } elseif ($type === Frame::class) {
                $bindParams[] = $frame;
            } elseif ($type === Request::class) {
                $bindParams[] = Session::mustGet()->getRequest();
            } else {
                $bindParams[] = null;
            }
        }

        return $bindParams;
    }
}
