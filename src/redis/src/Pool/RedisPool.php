<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Pool;

use Swoft\App;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Swoft\Pool\ConnectionInterface;
use Swoft\Pool\ConnectionPool;
use Swoft\Redis\Pool\Config\RedisPoolConfig;
use Swoft\Redis\RedisConnection;
use Swoft\Redis\SyncRedisConnection;

/**
 * Redis pool
 *
 * @Pool
 */
class RedisPool extends ConnectionPool
{
    /**
     * Config
     *
     * @Inject
     * @var RedisPoolConfig
     */
    protected $poolConfig;

    /**
     * Create connection
     *
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        if (App::isCoContext()) {
            $redis = new RedisConnection($this);
        } else {
            $redis = new SyncRedisConnection($this);
        }

        return $redis;
    }
}
