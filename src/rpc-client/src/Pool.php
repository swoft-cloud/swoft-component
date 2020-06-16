<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client;

use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Swoft\Rpc\Client\Exception\RpcClientException;

/**
 * Class Pool
 *
 * @since 2.0
 */
class Pool extends AbstractPool
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @return ConnectionInterface
     * @throws RpcClientException
     */
    public function createConnection(): ConnectionInterface
    {
        if (empty($this->client)) {
            throw new RpcClientException(sprintf('Pool(%s) client can not be null!', __CLASS__));
        }

        return $this->client->createConnection($this);
    }
}
