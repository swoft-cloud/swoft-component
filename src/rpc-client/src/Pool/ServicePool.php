<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Client\Pool;

use Swoft\App;
use Swoft\Pool\ConnectionInterface;
use Swoft\Pool\ConnectionPool;
use Swoft\Rpc\Client\Service\ServiceConnection;
use Swoft\Rpc\Client\Service\SyncServiceConnection;

/**
 * Service pool
 */
class ServicePool extends ConnectionPool
{
    /**
     * Create connection
     *
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        if (App::isCoContext()) {
            return new ServiceConnection($this);
        }

        return new SyncServiceConnection($this);
    }
}
