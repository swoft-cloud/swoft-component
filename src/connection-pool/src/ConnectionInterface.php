<?php declare(strict_types=1);


namespace Swoft\Connection\Pool;

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
     * Get connection unique id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Release connection
     */
    public function release(): void;

    /**
     * Get last time
     *
     * @return int
     */
    public function getLastTime(): int;


}