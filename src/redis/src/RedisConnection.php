<?php

namespace Swoft\Redis;

use Swoft\App;
use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnection;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Profile\RedisCommandProvider;
use Swoole\Coroutine\Redis as CoRedis;
use Swoft\Redis\Pool\Config\RedisPoolConfig;
use Swoft\Redis\Helper\RedisHelper;

/**
 * Redis connection
 *
 * @method bool select($dbindex)
 */
class RedisConnection extends AbstractConnection
{
    /**
     * @var Redis
     */
    protected $connection;

    /**
     * Create connection
     * @throws RedisException
     */
    public function createConnection()
    {
        $timeout = $this->pool->getTimeout();
        $address = $this->pool->getConnectionAddress();
        $config = RedisHelper::redisParseUri($address);

        /* @var RedisPoolConfig $poolConfig */
        $poolConfig = $this->pool->getPoolConfig();
        $serialize = $poolConfig->getSerialize();
        $serialize = ((int)$serialize == 0) ? false : true;

        // create
        $redis = new CoRedis();
        $host = $config['host'];
        $port = (int)$config['port'];
        $result = $redis->connect($host, $port, $serialize);
        if ($result == false) {
            $error = sprintf('Redis connection failure host=%s port=%d', $host, $port);
            throw new RedisException($error);
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
     * @return bool
     */
    public function check(): bool
    {
        return $this->connection->connected;
    }

    /**
     * @return mixed
     */
    public function receive()
    {
        $result = $this->connection->recv();
        $this->connection->setDefer(false);
        $this->recv = true;

        return $result;
    }

    /**
     * @throws RedisException
     */
    public function reconnect()
    {
        $this->createConnection();
    }

    /**
     * 设置延迟收包
     *
     * @param bool $defer
     */
    public function setDefer($defer = true)
    {
        $this->recv = false;
        $this->connection->setDefer($defer);
    }


    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @throws RedisException
     */
    public function __call($method, $arguments)
    {
        /* @var RedisCommandProvider $commandProvider */
        $commandProvider = App::getBean(RedisCommandProvider::class);
        $command = $commandProvider->createCommand($method, $arguments);
        $arguments = $command->getArguments();
        $method = $command->getId();

        return PhpHelper::call([$this->connection, $method], $arguments);
    }
}
