<?php declare(strict_types=1);


namespace Swoft\Redis\Contract;

/**
 * Class ConnectionInterface
 *
 * @since 2.0
 */
interface ConnectionInterface
{
    /**
     * Create client
     */
    public function createClient(): void;

    /**
     * Create cluster client
     */
    public function createClusterClient(): void;

    /**
     * @param string $key
     * @param array  $keys
     *
     * @return array
     */
    public function hMGet(string $key, array $keys): array;

    /**
     * @param string $key
     * @param array  $scoreValues
     *
     * @return int
     */
    public function zAdd(string $key, array $scoreValues): int;

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array;

    /**
     * Execute commands in a pipeline.
     *
     * @param  callable $callback
     *
     * @return array
     */
    public function pipeline(callable $callback): array;

    /**
     * Execute commands in a transaction.
     *
     * @param  callable $callback
     *
     * @return array
     */
    public function transaction(callable $callback): array;
}