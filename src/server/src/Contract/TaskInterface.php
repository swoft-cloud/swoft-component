<?php declare(strict_types=1);

namespace Swoft\Server\Contract;

use Swoft\Task\Exception\TaskException;
use Swoole\Server;
use Swoole\Server\Task as SwooleTask;

/**
 * Class TaskInterface
 *
 * @since 2.0
 */
interface TaskInterface
{
    /**
     * @param Server     $server
     * @param SwooleTask $task
     *
     * @throws TaskException
     */
    public function onTask(Server $server, SwooleTask $task): void;
}
