<?php

namespace Swoft\Http\Server;

use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\HttpErrorHandlerInterface;

/**
 * Class HttpErrorHandler
 * @since 2.0
 */
abstract class HttpErrorHandler
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
