<?php

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;

/**
 * Interface TaskInterface
 *
 * @since 2.0
 */
interface TaskInterface
{
    /**
     * Task event
     *
     * @param CoServer $serv
     * @param int      $taskId
     * @param int      $srcWorkerId
     * @param mixed    $data
     */
    public function onTask(CoServer $server, int $taskId, int $srcWorkerId, $data): void;
}