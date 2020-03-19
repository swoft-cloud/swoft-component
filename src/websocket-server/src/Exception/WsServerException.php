<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Exception;

use Swoft\Server\Exception\ServerException;

/**
 * Class WsServerException
 *
 * @since 2.0
 */
class WsServerException extends ServerException
{
    /**
     * @param string $message
     * @param int    $code
     *
     * @return WsServerException
     */
    public static function make(string $message, int $code = 500): self
    {
        return new static($message, $code);
    }
}
