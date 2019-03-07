<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

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
     * @param Server $server
     * @param int      $srcWorkerId
     * @param mixed    $message
     */
    public function onPipeMessage(Server $server, int $srcWorkerId, $message): void;
}