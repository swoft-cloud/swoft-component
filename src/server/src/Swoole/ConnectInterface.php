<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

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
     * @param Server $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onConnect(Server $server, int $fd, int $reactorId): void;
}
