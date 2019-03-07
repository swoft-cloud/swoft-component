<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

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
     * @param Server $server
     * @param int      $fd
     */
    public function onBufferFull(Server $server, int $fd): void;
}
