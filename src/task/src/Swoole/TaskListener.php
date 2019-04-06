<?php declare(strict_types=1);


namespace Swoft\Task\Swoole;


use Swoft\Server\Swoole\TaskInterface;
use Swoole\Server;

/**
 * Class TaskListener
 *
 * @since 2.0
 */
class TaskListener implements TaskInterface
{
    /**
     * @param Server $server
     * @param int    $taskId
     * @param int    $srcWorkerId
     * @param mixed  $data
     *
     * @return mixed
     */
    public function onTask(Server $server, int $taskId, int $srcWorkerId, $data)
    {
        
    }
}