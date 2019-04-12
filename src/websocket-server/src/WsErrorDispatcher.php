<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Exception\Dispatcher;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\ErrorHandlers;
use Swoft\Error\ErrorType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Contract\HandShakeErrorHandlerInterface;
use Swoft\WebSocket\Server\Contract\MessageErrorHandlerInterface;
use Swoft\WebSocket\Server\Contract\OpenErrorHandlerInterface;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoole\WebSocket\Frame;

/**
 * Class WsErrorDispatcher
 * @since 2.0
 * @Bean()
 */
class WsErrorDispatcher
{
    /**
     * @param \Throwable $e
     * @param Response   $response
     * @return Response
     * @throws \Throwable
     */
    public function handshakeError(\Throwable $e, Response $response): Response
    {
        if ($e instanceof WsModuleRouteException) {
            return $response
                ->withStatus(404)
                ->withAddedHeader('Failure-Reason', 'Route not found');
        }

        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);

        /** @var HandShakeErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::WS_HS)) {
            return $handler->handle($e, $response);
        }

        return $response->withStatus(500)->withContent($e->getMessage());
    }

    /**
     * @param \Throwable $e
     * @param Frame      $frame
     * @throws \Throwable
     */
    public function messageError(\Throwable $e, Frame $frame): void
    {
        if ($e instanceof WsMessageRouteException) {
            \server()->push($frame->fd, $e->getMessage());
            return;
        }

        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);

        /** @var MessageErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::WS_MSG)) {
            $handler->handle($e, $frame);
            return;
        }

        \server()->push($frame->fd, $e->getMessage());
    }

    /**
     * @param \Throwable $e
     * @param Request $request
     * @throws \Throwable
     */
    public function openError(\Throwable $e, Request $request): void
    {
        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);

        /** @var OpenErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::WS_OPN)) {
            $handler->handle($e, $request);
            return;
        }

        // TODO ?
    }
}
