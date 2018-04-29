<?php

namespace Swoft\Task;

use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Core\Coroutine;
use Swoft\Event\AppEvent;
use Swoft\Helper\PhpHelper;
use Swoft\Task\Bean\Collector\TaskCollector;
use Swoft\Task\Event\Events\BeforeTaskEvent;
use Swoft\Task\Event\TaskEvent;
use Swoft\Task\Helper\TaskHelper;

/**
 * The task executor
 *
 * @Bean()
 */
class TaskExecutor
{
    /**
     * @param string $data
     *
     * @return mixed
     */
    public function run(string $data)
    {
        $map = TaskHelper::unpack($data);

        $name   = $map['name'];
        $type   = $map['type'];
        $method = $map['method'];
        $params = $map['params'];
        $logId  = $map['logid'] ?? uniqid('', true);
        $spanId = $map['spanid'] ?? 0;

        $collector = TaskCollector::getCollector();
        if (!isset($collector['task'][$name])) {
            return false;
        }

        list(, $coroutine) = $collector['task'][$name];
        $task = App::getBean($name);

        if ($coroutine) {
            $result = $this->runCoTask($task, $method, $params, $logId, $spanId, $name, $type);
        } else {
            $result = $this->runSyncTask($task, $method, $params, $logId, $spanId, $name, $type);
        }

        return $result;
    }

    /**
     * @param object $task
     * @param string $method
     * @param array  $params
     * @param string $logId
     * @param int    $spanId
     * @param string $name
     * @param string $type
     *
     * @return mixed
     */
    private function runSyncTask($task, string $method, array $params, string $logId, int $spanId, string $name, string $type)
    {
        $this->beforeTask($logId, $spanId, $name, $method, $type, \get_parent_class($task));
        $result = PhpHelper::call([$task, $method], $params);
        $this->afterTask($type);

        return $result;
    }

    /**
     * @param object $task
     * @param string $method
     * @param array  $params
     * @param string $logId
     * @param int    $spanId
     * @param string $name
     * @param string $type
     *
     * @return bool
     */
    private function runCoTask($task, string $method, array $params, string $logId, int $spanId, string $name, string $type): bool
    {
        return Coroutine::create(function () use ($task, $method, $params, $logId, $spanId, $name, $type) {
            $this->beforeTask($logId, $spanId, $name, $method, $type, \get_parent_class($task));
            PhpHelper::call([$task, $method], $params);
            $this->afterTask($type);
        });
    }

    /**
     * @param string $logId
     * @param int $spanId
     * @param string $name
     * @param string $method
     * @param string $type
     * @param string $taskClass
     * @throws \InvalidArgumentException
     */
    private function beforeTask(string $logId, int $spanId, string $name, string $method, string $type, string $taskClass)
    {
        $event = new BeforeTaskEvent(TaskEvent::BEFORE_TASK, $logId, $spanId, $name, $method, $type, $taskClass);
        App::trigger($event);
    }

    /**
     * @param string $type
     * @throws \InvalidArgumentException
     */
    private function afterTask(string $type)
    {
        // Release system resources
        App::trigger(AppEvent::RESOURCE_RELEASE);

        App::trigger(TaskEvent::AFTER_TASK, null, $type);
    }
}
