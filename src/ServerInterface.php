<?php

namespace Swoft\Server;

/**
 * Interface ServerInterface
 *
 * @since 2.0
 */
interface ServerInterface
{
    /**
     * Start server
     *
     * @return void
     */
    public function start(): void;

    /**
     * Stop server
     *
     * @return void
     */
    public function stop(): void;

    /**
     * Stop server
     *
     * @return void
     */
    public function restart(): void;

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @return int
     */
    public function getPort(): int;

    /**
     * @return int
     */
    public function getMode(): int;

    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @return array
     */
    public function getSetting(): array;

    /**
     * @return array
     */
    public function getOn(): array;
}