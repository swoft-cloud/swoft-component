<?php

namespace Swoft\WebSocket\Server\Contract;

use Swoft\Http\Message\Response;

/**
 * Interface ErrorHandlerInterface
 *
 * @since 1.0
 */
interface WsErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @return Response
     */
    public function handleHandshakeError(\Throwable $e): Response;

    /**
     * @param \Throwable $e
     */
    public function handleMessageError(\Throwable $e): void;
}
