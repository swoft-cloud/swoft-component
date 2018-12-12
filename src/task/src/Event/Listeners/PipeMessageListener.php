<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Event\Listeners;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\AppEvent;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Pipe\PipeMessage;
use Swoft\Task\Task;

/**
 * The pipe message listener
 *
 * @Listener(event=AppEvent::PIPE_MESSAGE)
 */
class PipeMessageListener implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     * @throws \Swoft\Task\Exception\TaskException
     */
    public function handle(EventInterface $event)
    {
        $params = $event->getParams();
        if (\count($params) < 3) {
            return;
        }

        list($type, $data, $srcWorkerId) = $params;

        if ($type !== PipeMessage::MESSAGE_TYPE_TASK) {
            return;
        }

        $type       = $data['type'];
        $taskName   = $data['name'];
        $params     = $data['params'];
        $timeout    = $data['timeout'];
        $methodName = $data['method'];

        // deliver task
        Task::deliver($taskName, $methodName, $params, $type, $timeout);
    }
}
