<?php

namespace Swoft\WebSocket\Server\Exception;

/**
 * Class WsServerException
 * @since 2.0
 */
class WsServerException extends \RuntimeException
{
    /**
     * @param string $message
     * @param int    $code
     * @return WsServerException
     */
    public static function make(string $message, int $code = 500): WsServerException
    {
        return new static($message, $code);
    }
}
