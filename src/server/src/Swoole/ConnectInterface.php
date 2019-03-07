<?php declare(strict_types=1);

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
    public function onConnect(CoServer $server, int $fd, int $reactorId): void;
}
