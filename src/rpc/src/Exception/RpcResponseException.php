<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Exception;

class RpcResponseException extends RpcException
{
    /**
     * @var mixed
     */
    protected $response;

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return RpcResponseException
     */
    public function setResponse($response): self
    {
        $this->response = $response;
        return $this;
    }
}
