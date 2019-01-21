<?php

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;


/**
 * Interface ConnectInterface
 *
 * @since 2.0
 */
interface ConnectInterface
{
    /**
     * Connect event
     *
     * @param CoServer $server
     * @param int      $fd
     * @param int      $reactorId
     */
    function onConnect(CoServer $server, int $fd, int $reactorId): void;
}