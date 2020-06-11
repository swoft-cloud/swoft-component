<?php declare(strict_types=1);


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
