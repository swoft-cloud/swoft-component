<?php declare(strict_types=1);


namespace Swoft\Redis;

use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;

/**
 * Class Pool
 *
 * @since 2.0
 */
class Pool extends AbstractPool
{
    /**
     * Default pool
     */
    const DEFAULT_POOL = 'redis.pool';

    /**
     * @var RedisDb
     */
    protected $redisDb;

    /**
     * @return ConnectionInterface
     * @throws Exception\RedisException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->redisDb->createConnection($this);
    }
}