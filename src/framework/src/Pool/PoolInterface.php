<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Pool;

/**
 * Interface PoolInterface
 */
interface PoolInterface
{
    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface;

    /**
     * Get a connection
     *
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface;

    /**
     * Relesea the connection
     *
     * @param ConnectionInterface $connection
     */
    public function release(ConnectionInterface $connection);

    /**
     * @return string
     */
    public function getConnectionAddress(): string;

    /**
     * @return PoolConfigInterface
     */
    public function getPoolConfig(): PoolConfigInterface;

    /**
     * @return int
     */
    public function getTimeout(): int;
}
