<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

/**
 * Interface BufferEmptyInterface
 *
 * @since 2.0
 */
interface BufferEmptyInterface
{
    /**
     * Buffer empty event
     *
     * @param Server $server
     * @param int      $fd
     */
    public function onBufferEmpty(Server $server, int $fd): void;
}
