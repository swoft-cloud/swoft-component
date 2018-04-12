<?php

namespace Swoft\Redis;

use Swoft\App;
use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnection;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Profile\RedisCommandProvider;
use Swoole\Coroutine\Redis as CoRedis;
use Swoft\Redis\Pool\Config\RedisPoolConfig;

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
     */
    public function createConnection()
    {
        $timeout = $this->pool->getTimeout();
        $address = $this->pool->getConnectionAddress();
        $config  = $this->parseUri($address);

        /* @var RedisPoolConfig $poolConfig */
        $poolConfig = $this->pool->getPoolConfig();
        $serialize  = $poolConfig->getSerialize();
        $serialize  = ((int)$serialize == 0) ? false : true;

        // create
        $redis  = new CoRedis();
        $host   = $config['host'];
        $port   = (int)$config['port'];
        $result = $redis->connect($host, $port, $serialize);
        if ($result == false) {
            $error = sprintf('Redis connection failure host=%s port=%d', $host, $port);
            App::error($error);
            throw new RedisException($error);
        }
        if (isset($config['auth']) && false === $redis->auth($config['auth'])) {
            $error = sprintf('Redis connection authentication failed host=%s port=%d auth=%s', $host, $port, (string)$config['auth']);
            App::error($error);
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
     * Parse uri
     *
     * @param string $uri `tcp://127.0.0.1:6379/1?auth=password`
     *
     * @return array
     * @throws RedisException
     */
    protected function parseUri(string $uri)
    {
        $parseAry = parse_url($uri);
        if (!isset($parseAry['host']) || !isset($parseAry['port'])) {
            $error = sprintf('Redis Connection format is incorrect uri=%s, eg:tcp://127.0.0.1:6379/1?auth=password', $uri);
            App::error($error);
            throw new RedisException($error);
        }
        isset($parseAry['path']) && $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query']?? '';
        parse_str($query, $options);
        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);

        return $configs;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        /* @var RedisCommandProvider $commandProvider */
        $commandProvider = App::getBean(RedisCommandProvider::class);
        $command         = $commandProvider->createCommand($method, $arguments);
        $arguments       = $command->getArguments();
        $method          = $command->getId();

        return PhpHelper::call([$this->connection, $method], $arguments);
    }
}
