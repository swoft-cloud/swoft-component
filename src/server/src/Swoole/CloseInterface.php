<?php

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;


/**
 * Interface CloseInterface
 *
 * @since 2.0
 */
interface CloseInterface
{
    /**
     * Close event
     *
     * @param CoServer $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onClose(CoServer $server, int $fd, int $reactorId): void;
}