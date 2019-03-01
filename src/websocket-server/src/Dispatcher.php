<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\App;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Contract\ModuleInterface;
use Swoft\WebSocket\Server\Exception\WsContextException;
use Swoft\WebSocket\Server\Exception\WsServerException;
use Swoft\WebSocket\Server\Exception\WsRouteException;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class WsDispatcher
 * @package Swoft\WebSocket\Server
 *
 * @Bean("wsDispatcher")
 */
class Dispatcher
{
    /**
     * dispatch handshake request
     * @param Request  $request
     * @param Response $response
     * @return array eg. [status, response]
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \InvalidArgumentException
     */
    public function handshake(Request $request, Response $response): array
    {
        try {
            $path = $request->getUri()->getPath();
            [$className,] = $this->getHandler($path);

            \server()->log("found handler for path '$path', ws controller is $className", [], 'debug');
        } catch (\Throwable $e) {
            /* @var ErrorHandler $errorHandler */
            // $errorHandler = \bean(ErrorHandler::class);
            // $response = $errorHandler->handle($e);
            if ($e instanceof WsRouteException) {
                return [
                    ModuleInterface::REJECT,
                    $response->withStatus(404)->withAddedHeader('Failure-Reason', 'Route not found')
                ];
            }

            // other error
            throw new WsServerException('handshake error: ' . $e->getMessage(), -500, $e);
        }

        /** @var ModuleInterface $handler */
        $handler = \bean($className);

        if (!\method_exists($handler, 'checkHandshake')) {
            return [
                ModuleInterface::ACCEPT,
                $response->withAddedHeader('swoft-ws-handshake', 'auto')
            ];
        }

        return $handler->checkHandshake($request, $response);
    }

    /**
     * @param Server  $server
     * @param Request $request
     * @param int     $fd
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \InvalidArgumentException
     */
    public function open(Server $server, Request $request, int $fd)
    {
        $path = $request->getUri()->getPath();
        list($className,) = $this->getHandler($path);

        /** @var ModuleInterface $handler */
        $handler = \bean($className);

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

        try {
            if (!$path = WebSocketContext::getMeta('path', $fd)) {
                throw new WsContextException("The connection info has lost of the fd#$fd, on message");
            }

            $className = $this->getHandler($path)[0];

            \server()->log("call ws controller $className, method is 'onMessage'", [], 'debug');

            /** @var ModuleInterface $handler */
            $handler = \bean($className);
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

            /** @var ModuleInterface $handler */
            $handler = \bean($className);

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
        $router = \bean('wsRouter');
        [$status, $info] = $router->getHandler($path);

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
