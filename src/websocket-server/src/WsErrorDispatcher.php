<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Error\DefaultErrorDispatcher;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Contract\CloseErrorHandlerInterface;
use Swoft\WebSocket\Server\Contract\HandshakeErrorHandlerInterface;
use Swoft\WebSocket\Server\Contract\MessageErrorHandlerInterface;
use Swoft\WebSocket\Server\Contract\OpenErrorHandlerInterface;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoft\WebSocket\Server\Message\Response as MsgResponse;
use Swoole\WebSocket\Frame;
use Throwable;

/**
 * Class WsErrorDispatcher
 *
 * @since 2.0
 * @Bean()
 */
class WsErrorDispatcher
{
    /**
     * @Inject()
     * @var ErrorManager
     */
    private $errorManager;

    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     * @throws Throwable
     */
    public function handshakeError(Throwable $e, Response $response): Response
    {
        /** @var HandshakeErrorHandlerInterface $errHandler */
        if ($errHandler = $this->errorManager->match($e, ErrorType::WS_HS)) {
            return $errHandler->handle($e, $response);
        }

        // TODO should handle it?
        if ($e instanceof WsModuleRouteException) {
            return $response->withStatus(404)->withAddedHeader('Failure-Reason', 'Route not found');
        }

        // No handler, use default error dispatcher
        $this->defaultHandle($e);

        return $response->withStatus(500)->withContent($e->getMessage());
    }

    /**
     * @param Throwable $e
     * @param Request   $request
     *
     * @throws Throwable
     */
    public function openError(Throwable $e, Request $request): void
    {
        /** @var OpenErrorHandlerInterface $errHandler */
        if ($errHandler = $this->errorManager->match($e, ErrorType::WS_OPN)) {
            $errHandler->handle($e, $request);
            return;
        }

        // No handler, use default error dispatcher
        $this->defaultHandle($e);
    }

    /**
     * @param Throwable   $e
     * @param Frame       $frame
     * @param MsgResponse $response
     *
     * @return MsgResponse
     */
    public function messageError(Throwable $e, Frame $frame, MsgResponse $response): MsgResponse
    {
        /** @var MessageErrorHandlerInterface $errHandler */
        if ($errHandler = $this->errorManager->match($e, ErrorType::WS_MSG)) {
            $errHandler->handle($e, $frame);
        } elseif ($e instanceof WsMessageRouteException) {
            // Message command not found
            $response->setFd($frame->fd)->setContent($e->getMessage());
        } else {
            // No handler, use default error dispatcher
            $this->defaultHandle($e);
        }

        return $response;
    }

    /**
     * @param Throwable $e
     * @param int       $fd
     *
     * @throws Throwable
     */
    public function closeError(Throwable $e, int $fd): void
    {
        /** @var CloseErrorHandlerInterface $errHandler */
        if ($errHandler = $this->errorManager->match($e, ErrorType::WS_CLS)) {
            $errHandler->handle($e, $fd);
            return;
        }

        // No handler, use default error dispatcher
        $this->defaultHandle($e);
    }

    /**
     * @param Throwable $e
     */
    protected function defaultHandle(Throwable $e): void
    {
        /** @var DefaultErrorDispatcher $defDispatcher */
        $defDispatcher = Swoft::getSingleton(DefaultErrorDispatcher::class);
        $defDispatcher->run($e);
    }
}
