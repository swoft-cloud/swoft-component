<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Exception\WsMessageException;
use Swoft\WebSocket\Server\Exception\WsMessageParseException;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoft\WebSocket\Server\MessageParser\RawTextParser;
use Swoft\WebSocket\Server\Router\Router;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class WsDispatcher
 *
 * @since 1.0
 * @Bean("wsDispatcher")
 */
class WsDispatcher
{
    /**
     * Dispatch handshake request
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return array eg. [status, response]
     * @throws \Swoft\WebSocket\Server\Exception\WsModuleRouteException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function handshake(Request $request, Response $response): array
    {
        /** @var Router $router */
        $router = BeanFactory::getSingleton('wsRouter');

        /** @var Connection $conn */
        $conn = Session::mustGet();
        $path = $request->getUriPath();

        if (!$info = $router->match($path)) {
            throw new WsModuleRouteException(\sprintf(
                'The requested websocket route path "%s" is not exist!',
                $path
            ));
        }

        $class = $info['class'];
        $conn->setModuleInfo($info);

        \server()->log("Handshake: found handler for path '$path', ws module class is $class", [], 'debug');

        /** @var WsModuleInterface $module */
        $module = BeanFactory::getSingleton($class);

        // Call user method
        if ($method = $info['eventMethods']['handshake'] ?? '') {
            return $module->$method($request, $response);
        }

        // Auto handshake
        return [true, $response->withAddedHeader('swoft-ws-handshake', 'auto')];
    }

    /**
     * Dispatch ws message
     * @param Server $server
     * @param Frame  $frame
     * @throws \Throwable
     */
    public function message(Server $server, Frame $frame): void
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
        /** @var MessageParserInterface $msgParser */
        $msgParser = BeanFactory::getSingleton($parseClass);
        if (!$msgParser) {
            throw new WsMessageException("message parser bean '$parseClass' is not exists");
        }

        try {
            $body = $msgParser->decode($frame->data);
        } catch (\Throwable $e) {
            throw new WsMessageParseException("parse message error '{$e->getMessage()}", 500, $e);
        }

        $data  = $body['data'] ?? null;
        $cmdId = $body['cmd'] ?? $info['defaultCommand'];

        /** @var Router $router */
        $router = \Swoft::getBean('wsRouter');

        [$status, $handler] = $router->matchCommand($info['path'], $cmdId);
        if ($status === Router::NOT_FOUND) {
            throw new WsMessageRouteException("message command '$cmdId' is not found, in module {$info['path']}");
        }

        [$ctlClass, $ctlMethod] = $handler;

        \server()->log("Message: conn#{$fd} call message command handler '{$ctlClass}::{$ctlMethod}'", $body, 'debug');

        $object = BeanFactory::getBean($ctlClass);
        $params = $this->getBindParams($ctlClass, $ctlMethod, $frame, $data);
        $result = $object->$ctlMethod(...$params);

        // If result is not null, encode and replay
        if ($result !== null) {
            $server->push($fd, $msgParser->encode($result));
        }
    }

    /**
     * Dispatch ws close handle
     * @param Server $server
     * @param int    $fd
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function close(Server $server, int $fd): void
    {
        /** @var Connection $conn */
        $conn = Session::mustGet();
        $info = $conn->getModuleInfo();

        $method = $info['eventMethods']['close'] ?? '';
        if (!$method) {
            return;
        }

        $class = $info['class'];
        \server()->log("conn#{$fd} call ws close handler '{$class}::{$method}'", [], 'debug');

        /** @var WsModuleInterface $module */
        $module = BeanFactory::getSingleton($class);
        $module->$method($server, $fd);
    }

    /**
     * Get method bounded params
     *
     * @param string $class
     * @param string $method
     * @param Frame  $frame
     * @param mixed  $data
     * @return array
     * @throws \ReflectionException
     */
    private function getBindParams(string $class, string $method, Frame $frame, $data): array
    {
        $classInfo = \Swoft::getReflection($class);
        if (!isset($classInfo['methods'][$method])) {
            return [];
        }

        // binding params
        $bindParams   = [];
        $methodParams = $classInfo['methods'][$method]['params'];

        /**
         * @var string          $name
         * @var \ReflectionType $paramType
         * @var mixed           $devVal
         */
        foreach ($methodParams as [$name, $paramType, $devVal]) {
            // Defined type of the param
            $type = $paramType->getName();

            if ($name === 'data') {
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
