<?php

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
    public function onReceive(SCoServerwooleServer $server, int $fd, int $reactorId, string $data): void;
}