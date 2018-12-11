<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Exception;

class RpcStatusException extends RpcException
{
    /**
     * @var array
     */
    protected $response = [];

    /**
     * @return mixed|null
     */
    public function getResponseMessage()
    {
        return $this->getResponse()['msg'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getStatus()
    {
        return $this->getResponse()['status'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->getResponse()['data'] ?? null;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     * @return RpcStatusException
     */
    public function setResponse(array $response): self
    {
        $this->response = $response;
        return $this;
    }
}
