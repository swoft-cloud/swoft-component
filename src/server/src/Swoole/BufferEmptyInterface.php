<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;

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
     * @param CoServer $server
     * @param int      $fd
     */
    public function onBufferEmpty(CoServer $server, int $fd): void;
}