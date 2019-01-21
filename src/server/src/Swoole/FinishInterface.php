<?php

namespace Swoft\Server\Swoole;

use Co\Server as CoServer;


/**
 * Interface FinishInterface
 *
 * @since 2.0
 */
interface FinishInterface
{
    /**
     * Finish event
     *
     * @param CoServer $server
     * @param int      $taskId
     * @param string   $data
     */
    public function onFinish(CoServer $server, int $taskId, string $data): void;
}