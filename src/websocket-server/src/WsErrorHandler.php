<?php

namespace Swoft\Http\Server;

use Swoft\Error\ErrorHandlerInterface;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Contract\WsErrorHandlerInterface;

/**
 * Class WsErrorHandler
 * @since 2.0
 */
abstract class WsErrorHandler implements ErrorHandlerInterface, WsErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @return void
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(\Throwable $e): void
    {
        $response = $this->handleHttpError($e);
        $response->send();
    }

    /**
     * @param \Throwable $e
     * @return Response
     */
    abstract public function handleHttpError(\Throwable $e): Response;
}
