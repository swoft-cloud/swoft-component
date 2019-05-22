<?php declare(strict_types=1);


namespace Swoft\Connection\Pool\Contract;


/**
 * Class ConnectionInterface
 *
 * @since 2.0
 */
interface ConnectionInterface
{
    /**
     * Create connection
     */
    public function create(): void;

    /**
     * Reconnect connection
     */
    public function reconnect(): bool;

    /**
     * Get connection id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Release connection
     *
     * @param bool $force
     */
    public function release(bool $force = false): void;

    /**
     * Get last time
     *
     * @return int
     */
    public function getLastTime(): int;

    /**
     * Set whether to release
     *
     * @param bool $release
     */
    public function setRelease(bool $release): void;
}