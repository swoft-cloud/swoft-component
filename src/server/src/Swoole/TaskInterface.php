<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

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
     * @param Server $server
     * @param int      $taskId
     * @param int      $srcWorkerId
     * @param mixed    $data
     */
    public function onTask(Server $server, int $taskId, int $srcWorkerId, $data): void;
}
