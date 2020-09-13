<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Exception\Handler;

use Swoft\Error\ErrorType;
use Swoft\Rpc\Server\Contract\RpcServerErrorHandlerInterface;

/**
 * Class AbstractRpcServerErrorHandler
 *
 * @since 2.0
 */
abstract class AbstractRpcServerErrorHandler implements RpcServerErrorHandlerInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::RPC;
    }
}
