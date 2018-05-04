<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis;

use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnection;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Helper\RedisHelper;
use Swoft\Redis\Pool\Config\RedisPoolConfig;

/**
 * Sync redis connection
 */
class SyncRedisConnection extends AbstractConnection
{
    /**
     * @var \Redis
     */
    protected $connection;

    /**
     * @return void
     * @throws RedisException
     */
    public function createConnection()
    {
        $timeout = $this->pool->getTimeout();
        $address = $this->pool->getConnectionAddress();
        $config = RedisHelper::parseUri($address);

        /* @var RedisPoolConfig $poolConfig */
        $poolConfig = $this->pool->getPoolConfig();
        $prefix = $poolConfig->getPrefix();
        $serialize = $poolConfig->getSerialize();
        $serialize = ((int)$serialize == 0) ? false : true;

        // init
        $redis = new \Redis();
        $host = $config['host'];
        $port = (int)$config['port'];
        $result = $redis->connect($host, $port, $timeout);
        if ($result == false) {
            $error = sprintf('Redis connection failure host=%s port=%d', $host, $port);
            throw new RedisException($error);
        }
        if ($serialize) {
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        }
        if ($prefix !== '' && is_string($prefix)) {
            $redis->setOption(\Redis::OPT_PREFIX, $prefix);
        }
        if (isset($config['auth']) && false === $redis->auth($config['auth'])) {
            $error = sprintf('Redis connection authentication failed host=%s port=%d auth=%s', $host, $port, (string)$config['auth']);
            throw new RedisException($error);
        }
        if (isset($config['database']) && $config['database'] < 16 && false === $redis->select($config['database'])) {
            $error = sprintf('Redis selection database failure host=%s port=%d database=%d', $host, $port, (int)$config['database']);
            throw new RedisException($error);
        }

        $this->connection = $redis;
    }

    /**
     * @return $this
     * @throws RedisException
     */
    public function reconnect()
    {
        $this->createConnection();

        return $this;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        try {
            $this->connection->ping();
            $connected = true;
        } catch (\Throwable $throwable) {
            $connected = false;
        }

        return $connected;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return PhpHelper::call([$this->connection, $method], $arguments);
    }
}
