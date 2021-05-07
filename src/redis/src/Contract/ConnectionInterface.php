<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     * @param array $keyValues
     * @param int   $ttl
     *
     * @return bool
     */
    public function mset(array $keyValues, int $ttl = 0): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string         $key
     * @param mixed          $value
     * @param int|array|null $timeout
     *
     * @return bool
     */
    public function set(string $key, $value, $timeout = null): bool;

    /**
     * Execute commands in a pipeline.
     *
     * @param callable $callback
     *
     * @return array
     */
    public function pipeline(callable $callback): array;

    /**
     * Execute commands in a transaction.
     *
     * @param callable $callback
     *
     * @return array
     */
    public function transaction(callable $callback): array;
}
