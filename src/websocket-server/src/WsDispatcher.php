<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Exception\WsContextException;
use Swoft\WebSocket\Server\Exception\WsRouteException;
use Swoft\WebSocket\Server\Exception\WsServerException;
use Swoft\WebSocket\Server\Router\Router;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class WsDispatcher
 * @package Swoft\WebSocket\Server
 *
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
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \InvalidArgumentException
     */
    public function handshake(Request $request, Response $response): array
    {
        $router = Container::$instance->getSingleton('wsRouter');

        try {
            /** @var Connection $conn */
            $conn = Session::mustGet();
            $path = $request->getUriPath();
            /** @var Router $router */
            [$status, $info] = $router->match($path);
            if ($status !== Router::FOUND) {
                throw new WsRouteException(sprintf(
                    'The requested websocket route "%s" path is not exist!',
                    $path
                ));
            }

            $class = $info['class'];
            $conn->setModule($info);

            \server()->log("found handler for path '$path', ws module class is $class", [], 'debug');

            /** @var WsModuleInterface $module */
            $module = Container::$instance->getSingleton($class);
            $method = $info['eventMethods']['handShake'] ?? '';

            // Auto handShake
            if (!$method) {
                return [true, $response->withAddedHeader('swoft-ws-handshake', 'auto')];
            }

            return $module->$method($request, $response);
        } catch (\Throwable $e) {
            /* @var ErrorHandler $errorHandler */
            // $errorHandler = \bean(ErrorHandler::class);
            // $response = $errorHandler->handle($e);
            if ($e instanceof WsRouteException) {
                return [
                    WsModuleInterface::REJECT,
                    $response->withStatus(404)->withAddedHeader('Failure-Reason', 'Route not found')
                ];
            }

            // Other error
            throw new WsServerException('handshake error: ' . $e->getMessage(), -500, $e);
        }
    }

    /**
     * @param Server  $server
     * @param Request $request
     * @param int     $fd
     *
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \InvalidArgumentException
     */
    public function open(Server $server, Request $request, int $fd): void
    {
        $path = $request->getUriPath();
        [$className,] = $this->getHandler($path);

        /** @var WsModuleInterface $handler */
        $handler = Container::$instance->getSingleton($className);

        if (\method_exists($handler, 'onOpen')) {
            $handler->onOpen($server, $request, $fd);
        }
    }

    /**
     * dispatch ws message
     * @param Server $server
     * @param Frame  $frame
     * @throws \InvalidArgumentException
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \Swoft\WebSocket\Server\Exception\WsContextException
     */
    public function message(Server $server, Frame $frame)
    {
        $fd = $frame->fd;
        /** @var Connection $conn */
        $conn = Session::get();
        $path = $conn->getMetaValue('path');

        try {
            if (!$path) {
                throw new WsContextException("The connection info has lost of the fd#$fd, on message");
            }

            $className = $this->getHandler($path)[0];

            \server()->log("call ws controller $className, method is 'onMessage'", [], 'debug');

            /** @var WsModuleInterface $handler */
            $handler = Container::$instance->getSingleton($className);
            $handler->onMessage($server, $frame);
        } catch (\Throwable $e) {
            \server()->log('error on handle message, ERR: ' . $e->getMessage(), [], 'error');

            /** @see \Swoft\Event\EventManager::hasListenerQueue() */
            if (\bean('eventManager')->hasListenerQueue(WsServerEvent::ON_ERROR)) {
                \Swoft::trigger(WsServerEvent::ON_ERROR, $frame, $e);
            } else {
                App::error($e->getMessage(), ['fd' => $fd, 'data' => $frame->data]);
                // close connection
                $server->close($fd);
            }
        }
    }

    /**
     * dispatch ws close
     * @param Server $server
     * @param int    $fd
     * @throws \InvalidArgumentException
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \Swoft\WebSocket\Server\Exception\WsContextException
     */
    public function close(Server $server, int $fd): void
    {
        try {
            if (!$path = WebSocketContext::getMeta('path', $fd)) {
                throw new WsContextException(
                    "The connection info has lost of the fd#$fd, on connection closed"
                );
            }

            $className = $this->getHandler($path)[0];

            /** @var WsModuleInterface $handler */
            $handler = Container::$instance->getSingleton($className);

            if (\method_exists($handler, 'onClose')) {
                $handler->onClose($server, $fd);
            }
        } catch (\Throwable $e) {
            App::error($e->getMessage(), ['fd' => $fd]);
            // App::trigger(WsEvent::ON_ERROR, $fd, $e);
        }
    }

    /**
     * @param string $path
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     */
    protected function getHandler(string $path): array
    {
        /** @var Router $router */
        $router = Container::$instance->getSingleton('wsRouter');
        [$status, $info] = $router->match($path);

        if ($status !== Router::FOUND) {
            throw new WsRouteException(sprintf(
                'The requested websocket route "%s" path is not exist!',
                $path
            ));
        }

        return [
            $info['handler'],
            $info['option'] ?? []
        ];
    }
}
