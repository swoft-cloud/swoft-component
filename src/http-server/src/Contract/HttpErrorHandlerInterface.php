<?php

namespace Swoft\Http\Server\Contract;

use Swoft\Http\Message\Response;

/**
 * Interface ErrorHandlerInterface
 *
 * @since 1.0
 */
interface HttpErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @return Response
     */
    public function handleHttpError(\Throwable $e): Response;
}
