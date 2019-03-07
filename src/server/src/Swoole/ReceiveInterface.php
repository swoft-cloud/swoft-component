<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;

/**
 * Interface ReceiveInterface
 *
 * @since 2.0
 */
interface ReceiveInterface
{
    /**
     * Receive event
     *
     * @param CoServer $server
     * @param int      $fd
     * @param int      $reactorId
     * @param string   $data
     */
    public function onReceive(CoServer $server, int $fd, int $reactorId, string $data): void;
}