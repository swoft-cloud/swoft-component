<?php declare(strict_types=1);


namespace Swoft\Redis\Contract;

/**
 * Class ConnectorInterface
 *
 * @since 2.0
 */
interface ConnectorInterface
{
    /**
     * @param array $config
     * @param array $option
     *
     * @return \Redis
     */
    public function connect(array $config, array $option): \Redis;

    /**
     * @param array $config
     * @param array $option
     *
     * @return \RedisCluster
     */
    public function connectToCluster(array $config, array $option): \RedisCluster;
}