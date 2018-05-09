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

use Swoft\App;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Pool\Config\RedisPoolConfig;
use Swoft\Redis\Profile\RedisCommandProvider;
use Swoole\Coroutine\Redis as CoRedis;

/**
 * Redis connection
 *
 * @method bool select($dbindex)
 */
class RedisConnection extends AbstractRedisConnection
{

    /**
     * Create connection
     *
     * @throws RedisException
     */
    public function createConnection()
    {
        $redis            = $this->initRedis();
        $this->connection = $redis;
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
<<<<<<< HEAD
     * Parse uri
     *
     * @param string $uri `tcp://127.0.0.1:6379/1?auth=password`
     *
     * @return array
     * @throws RedisException
     */
    protected function parseUri(string $uri): array
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
        unset($configs['path'], $configs['query']);

        return $configs;
    }

    /**
=======
>>>>>>> master
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws RedisException
     */
    public function __call($method, $arguments)
    {
        /* @var RedisCommandProvider $provider */
        $provider = \bean(RedisCommandProvider::class);
        $command = $provider->createCommand($method, $arguments);
        $arguments = $command->getArguments();
        $method = $command->getId();

        return parent::__call($method, $arguments);
    }


    /**
     * @param string $host
     * @param int    $port
     * @param int    $timeout
     *
     * @return CoRedis
     * @throws RedisException
     */
    protected function getConnectRedis(string $host, int $port, int $timeout): CoRedis
    {
        /* @var RedisPoolConfig $poolConfig */
        $poolConfig = $this->pool->getPoolConfig();
        $serialize  = $poolConfig->getSerialize();
        $serialize  = ((int)$serialize == 0) ? false : true;
        $redis      = new CoRedis();
        $result     = $redis->connect($host, $port, $serialize);
        if ($result == false) {
            $error = sprintf('Redis connection failure host=%s port=%d', $host, $port);
            throw new RedisException($error);
        }

        return $redis;
    }
}
