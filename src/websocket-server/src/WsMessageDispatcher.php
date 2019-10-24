<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use ReflectionException;
use ReflectionType;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\CLog;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Contract\MessageHandlerInterface;
use Swoft\WebSocket\Server\Contract\MiddlewareInterface;
use Swoft\WebSocket\Server\Contract\RequestInterface;
use Swoft\WebSocket\Server\Contract\ResponseInterface;
use Swoft\WebSocket\Server\Exception\WsMessageParseException;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\Message\Message;
use Swoft\WebSocket\Server\Message\Request;
use Swoft\WebSocket\Server\Message\Response;
use Swoft\WebSocket\Server\Router\Router;
use Swoole\WebSocket\Frame;
use Throwable;
use function server;

/**
 * Class WsMessageDispatcher
 *
 * @since 2.0
 *
 * @Bean("wsMsgDispatcher")
 */
class WsMessageDispatcher implements MiddlewareInterface
{
    /**
     * @Inject("wsRouter")
     * @var Router
     */
    private $router;

    /**
     * Dispatch ws message handle
     *
     * @param array    $module
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws WsMessageParseException
     * @throws WsMessageRouteException
     */
    public function dispatch(array $module, Request $request, Response $response): Response
    {
        /** @var Connection $conn */
        $conn  = Session::current();
        $frame = $request->getFrame();

        CLog::info('Message: message data parser is %s', $conn->getParserClass());

        // Parse message data and dispatch route handle
        try {
            $parser  = $conn->getParser();
            $message = $parser->decode($frame->data);

            // Set Message to request
            $request->setMessage($message);
        } catch (Throwable $e) {
            throw new WsMessageParseException("parse message error '{$e->getMessage()}", 500, $e);
        }

        /** @var Router $router */
        $path  = $module['path'];
        $cmdId = $message->getCmd() ?: $module['defaultCommand'];

        [$status, $route] = $this->router->matchCommand($path, $cmdId);
        if ($status === Router::NOT_FOUND) {
            throw new WsMessageRouteException("message command '$cmdId' is not found, in module {$path}");
        }

        [$ctlClass, $ctlMethod] = $route['handler'];

        $logMsg = "Message: conn#{$frame->fd} call message command handler '{$ctlClass}::{$ctlMethod}'";
        server()->log($logMsg, $message->toArray(), 'debug');

        $object = Swoft::getSingleton($ctlClass);
        $params = $this->getBindParams($ctlClass, $ctlMethod, $request, $response);
        $result = $object->$ctlMethod(...$params);

        if ($result && $result instanceof Response) {
            $response = $result;
        } elseif ($result !== null) {
            // Set user data and change default opcode
            $response->setData($result);
            $response->setOpcode((int)$route['opcode']);
        }

        return $response;
    }

    protected function dispatchMessage(Request $request, Response $response): ResponseInterface
    {

        return $response;
    }

    /**
     * @param RequestInterface|Request $request
     * @param MessageHandlerInterface  $handler
     *
     * @return ResponseInterface
     * @throws Swoft\Exception\SwoftException
     * @internal for middleware dispatching
     */
    public function process(RequestInterface $request, MessageHandlerInterface $handler): ResponseInterface
    {
        /** @var Response $response */
        $response = context()->getResponse();

        return $this->dispatchMessage($request, $response);
    }

    /**
     * Get method bounded params
     *
     * @param string   $class
     * @param string   $method
     * @param Request  $request
     * @param Response $response
     *
     * @return array
     * @throws ReflectionException
     */
    private function getBindParams(string $class, string $method, Request $request, Response $response): array
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

            if ($type === 'string' && $name === 'data') {
                $bindParams[] = $request->getRawData();
            } elseif ($type === Frame::class) {
                $bindParams[] = $request->getFrame();
            } elseif ($type === Message::class) {
                $bindParams[] = $request->getMessage();
            } elseif ($type === Request::class) {
                $bindParams[] = $request;
            } elseif ($type === Response::class) {
                $bindParams[] = $response;
            } else {
                $bindParams[] = null;
            }
        }

        return $bindParams;
    }
}
