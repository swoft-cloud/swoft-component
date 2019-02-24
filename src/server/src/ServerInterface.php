<?php

namespace Swoft\Server;

/**
 * Interface ServerInterface
 *
 * @since 2.0
 */
interface ServerInterface
{
    // swoole mode list
    public const MODE_LIST = [
        \SWOOLE_BASE    => 'Base',
        \SWOOLE_PROCESS => 'Process',
    ];

    // swoole socket type list
    public const TYPE_LIST = [
        \SWOOLE_SOCK_TCP         => 'TCP',
        \SWOOLE_SOCK_TCP6        => 'TCP6',
        \SWOOLE_SOCK_UDP         => 'UDP',
        \SWOOLE_SOCK_UDP6        => 'UDP6',
        \SWOOLE_SOCK_UNIX_DGRAM  => 'UNIX DGRAM',
        \SWOOLE_SOCK_UNIX_STREAM => 'UNIX STREAM',
    ];

    /**
     * Start server
     *
     * @return void
     */
    public function start(): void;

    /**
     * Stop server
     *
     * @return bool
     */
    public function stop(): bool;

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