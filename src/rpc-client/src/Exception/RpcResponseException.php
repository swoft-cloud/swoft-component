<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client\Exception;

use Exception;
use Swoft\Rpc\Response;

/**
 * Class RpcResponseException
 *
 * @since 2.0
 */
class RpcResponseException extends Exception
{
    /**
     * Response property ,it will be set when client get an error.
     *
     * @var $rpcResponse Response
     */
    private $rpcResponse;

    /**
     * @return Response
     */
    public function getRpcResponse():Response
    {
        return $this->rpcResponse;
    }

    /**
     * @param Response $rpcResponse
     */
    public function setRpcResponse(Response $rpcResponse): void
    {
        $this->rpcResponse = $rpcResponse;
    }
}
