<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Connection\Pool\Contract;

/**
 * Class PoolInterface
 *
 * @since 2.0
 */
interface PoolInterface
{
    /**
     * Initialize pool
     */
    public function initPool(): void;

    /**
     * Create connection
     *
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface;

    /**
     * Get connection from pool
     *
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface;

    /**
     * Release connection to pool
     *
     * @param ConnectionInterface $connection
     */
    public function release(ConnectionInterface $connection): void;

    /**
     * @return int
     */
    public function getConnectionId(): int;

    /**
     * Remove
     */
    public function remove(): void;

    /**
     * Close connections
     *
     * @return int
     */
    public function close(): int;
}
