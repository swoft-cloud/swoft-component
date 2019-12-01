<?php declare(strict_types=1);


namespace Swoft\Redis\Connector;

use Redis;
use RedisCluster;
use RedisClusterException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Redis\Contract\ConnectorInterface;
use Swoft\Redis\Exception\RedisException;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class PhpRedisConnector
 *
 * @since 2.0
 *
 * @Bean()
 */
class PhpRedisConnector implements ConnectorInterface
{
    /**
     * @param array $config
     * @param array $option
     *
     * @return Redis
     * @throws RedisException
     */
    public function connect(array $config, array $option): Redis
    {
        $client = new Redis();
        $this->establishConnection($client, $config);

        if (!empty($config['password'])) {
            $client->auth($config['password']);
        }

        if (!empty($config['database'])) {
            $client->select($config['database']);
        }

        if (!empty($config['read_timeout'])) {
            $client->setOption(Redis::OPT_READ_TIMEOUT, (string)$config['read_timeout']);
        }

        if (!empty($option['prefix'])) {
            $client->setOption(Redis::OPT_PREFIX, $option['prefix']);
        }

        if (!empty($option['serializer'])) {
            $client->setOption(Redis::OPT_SERIALIZER, (string)$option['serializer']);
        }

        if (!empty($option['scan'])) {
            $client->setOption(Redis::OPT_SCAN, (string)$option['scan']);
        }

        return $client;
    }

    /**
     * @param array $config
     * @param array $option
     *
     * @return RedisCluster
     * @throws RedisClusterException
     *
     * @see https://raw.githubusercontent.com/zgb7mtr/phpredis_cluster_phpdoc/master/src/RedisCluster.php
     */
    public function connectToCluster(array $config, array $option): RedisCluster
    {
        $servers     = array_map([$this, 'buildClusterConnectionString'], $config);
        $servers     = array_values($servers);         // Create a cluster setting two nodes as seeds
        $readTimeout = $option['read_timeout'] ?? 0;
        $timeout     = $option['timeout'] ?? 0;
        $persistent  = $option['persistent'] ?? false; // persistent connections to each node
        $name        = $option['name'] ?? null;
        $auth        = $option['auth'] ?? '';          // Connect with cluster using password.

        $parameters = compact('name', 'servers', 'timeout', 'readTimeout', 'persistent');
        $parameters = array_values($parameters);

        if (version_compare(phpversion('redis'), '4.3.0', '>=')) {
            $parameters[] = $auth;
        }

        $redisCluster = new RedisCluster(...$parameters);

        $this->setRedisClusterOptions($redisCluster, $option);

        return $redisCluster;
    }

    /**
     *  Set redis cluster option
     *
     * @param RedisCluster $redisCluster
     * @param array        $option
     */
    protected function setRedisClusterOptions(RedisCluster $redisCluster, array $option): void
    {
        if (!empty($option['prefix'])) {
            $redisCluster->setOption(RedisCluster::OPT_PREFIX, $option['prefix']);
        }

        if (!empty($option['serializer'])) {
            $redisCluster->setOption(RedisCluster::OPT_SERIALIZER, (string)$option['serializer']);
        }

        if (!empty($option['scan'])) {
            $redisCluster->setOption(RedisCluster::OPT_SCAN, (string)$option['scan']);
        }

        if (!empty($option['slave_failover'])) {
            $redisCluster->setOption(RedisCluster::OPT_SLAVE_FAILOVER, (string)$option['slave_failover']);
        }
    }

    /**
     * Build a single cluster seed string from array.
     *
     * @param array $server
     *
     * @return string
     */
    protected function buildClusterConnectionString(array $server): string
    {
        $allowParams = [
            'database',
            'password',
            'prefix',
            'read_timeout',
        ];

        $base = $server['host'] . ':' . $server['port'];

        $params = Arr::only($server, $allowParams);
        if ($query = Arr::query($params)) {
            $base .= '?' . $query;
        }

        return $base;
    }

    /**
     * Establish a connection with the Redis host.
     *
     * @param Redis $client
     * @param array $config
     *
     * @return void
     * @throws RedisException
     */
    protected function establishConnection(Redis $client, array $config): void
    {
        $parameters = [
            $config['host'],
            $config['port'],
            $config['timeout'],
            '',
            $config['retry_interval'],
        ];

        if (version_compare(phpversion('redis'), '3.1.3', '>=')) {
            $parameters[] = $config['read_timeout'];
        }

        $result = $client->connect(...$parameters);
        if ($result === false) {
            throw new RedisException(
                sprintf('Redis connect error(%s)', JsonHelper::encode($parameters, JSON_UNESCAPED_UNICODE))
            );
        }
    }
}
