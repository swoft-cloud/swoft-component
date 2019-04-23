<?php declare(strict_types=1);


namespace Swoft\Task\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\FinishInterface;
use Swoft\Task\TaskEvent;
use Swoole\Server;

/**
 * Class FinishListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class FinishListener implements FinishInterface
{
    /**
     * @param Server $server
     * @param int    $taskId
     * @param string $data
     *
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onFinish(Server $server, int $taskId, string $data): void
    {
        // Before finish
        \Swoft::trigger(TaskEvent::BEFORE_FINISH, null, $server, $taskId, $data);

        // Do finish
        \Swoft::trigger(TaskEvent::FINISH);

        // After finish
        \Swoft::trigger(TaskEvent::AFTER_FINISH);
    }
}