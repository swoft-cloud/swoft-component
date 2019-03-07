<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;

/**
 * Interface PipeMessageInterface
 *
 * @since 2.0
 */
interface PipeMessageInterface
{
    /**
     * Pipe message event
     *
     * @param CoServer $server
     * @param int      $srcWorkerId
     * @param mixed    $message
     */
    public function onPipeMessage(CoServer $server, int $srcWorkerId, $message): void;
}