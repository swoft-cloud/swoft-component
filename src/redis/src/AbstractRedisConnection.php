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

/**
 * Class AbstractRedisConnection
 *
 * @package Swoft\Redis
 */
abstract class AbstractRedisConnection extends AbstractConnection
{
    /**
     * @var Redis
     */
    protected $connection;

    /**
     * @return \Redis|Redis
     * @throws RedisException
     */
    protected function initRedis()
    {
        $timeout = $this->pool->getTimeout();
        $address = $this->pool->getConnectionAddress();
        $config  = $this->parseUri($address);

        $host  = $config['host'];
        $port  = (int)$config['port'];
        $redis = $this->getConnectRedis($host, $port, $timeout);
        if (isset($config['auth']) && false === $redis->auth($config['auth'])) {
            $error = sprintf('Redis connection authentication failed host=%s port=%d auth=%s', $host, (int)$port, (string)$config['auth']);
            throw new RedisException($error);
        }
        if (isset($config['database']) && $config['database'] < 16 && false === $redis->select($config['database'])) {
            $error = sprintf('Redis selection database failure host=%s port=%d database=%d', $host, (int)$port, (int)$config['database']);
            throw new RedisException($error);
        }

        return $redis;
    }

    /**
     * @return $this
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
     * @param string $uri
     *
     * @return array
     * @throws RedisException
     */
    protected function parseUri(string $uri): array
    {
        $parseAry = parse_url($uri);
        if (!isset($parseAry['host']) || !isset($parseAry['port'])) {
            $error = sprintf('Redis Connection format is incorrect uri=%s, eg:tcp://127.0.0.1:6379/1?auth=password', $uri);
            throw new RedisException($error);
        }
        isset($parseAry['path']) && $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query'] ?? '';
        parse_str($query, $options);
        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);

        return $configs;
    }

    /**
     * @param string $host
     * @param int    $port
     * @param int    $timeout
     *
     * @return Redis | \Redis
     */
    abstract protected function getConnectRedis(string $host, int $port, int $timeout);

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return PhpHelper::call([$this->connection, $method], $arguments);
    }
}
