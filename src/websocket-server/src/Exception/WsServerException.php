<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Exception;

use RuntimeException;

/**
 * Class WsServerException
 *
 * @since 2.0
 */
class WsServerException extends RuntimeException
{
    /**
     * @param string $message
     * @param int    $code
     * @return WsServerException
     */
    public static function make(string $message, int $code = 500): self
    {
        return new static($message, $code);
    }
}
