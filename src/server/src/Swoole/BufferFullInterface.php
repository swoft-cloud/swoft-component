<?php

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;


/**
 * Interface BufferFullInterface
 *
 * @since 2.0
 */
interface BufferFullInterface
{
    /**
     * Buffer full event
     *
     * @param CoServer $server
     * @param int      $fd
     */
    public function onBufferFull(CoServer $server, int $fd): void;
}