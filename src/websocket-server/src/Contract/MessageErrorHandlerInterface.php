<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Contract;

use Swoft\Error\Contract\ErrorHandlerInterface;
use Swoole\WebSocket\Frame;
use Throwable;

/**
 * Interface MessageErrorHandlerInterface
 *
 * @since 2.0
 */
interface MessageErrorHandlerInterface extends ErrorHandlerInterface
{
    /**
     * @param Throwable $e
     * @param Frame     $frame
     */
    public function handle(Throwable $e, Frame $frame): void;
}
