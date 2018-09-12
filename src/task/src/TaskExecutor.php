<?php

namespace Swoft\Task;

use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Core\Coroutine;
use Swoft\Event\AppEvent;
use Swoft\Helper\PhpHelper;
use Swoft\Task\Bean\Collector\TaskCollector;
use Swoft\Task\Event\Events\AfterTaskEvent;
use Swoft\Task\Event\Events\BeforeTaskEvent;
use Swoft\Task\Event\TaskEvent;
use Swoft\Task\Helper\TaskHelper;
use function bean;
use function get_parent_class;
use function uniqid;

/**
 * @Bean()
 */
class TaskExecutor
{
    /**
     * @return mixed
     */
    public function run(string $data)
    {
        $data = TaskHelper::unpack($data);

        $name = $data['name'];
        $type = $data['type'];
        $method = $data['method'];
        $params = $data['params'];
        $logid = $data['logid'] ?? uniqid('', true);
        $spanid = $data['spanid'] ?? 0;


        $collector = TaskCollector::getCollector();
        if (! isset($collector['task'][$name])) {
            return false;
        }

        list(, $coroutine) = $collector['task'][$name];
        $task = bean($name);

        if ($coroutine) {
            $result = $this->runCoTask($task, $method, $params, $logid, $spanid, $name, $type);
        } else {
            $result = $this->runAsyncTask($task, $method, $params, $logid, $spanid, $name, $type);
        }

        return $result;
    }

    /**
     * @param object $task
     * @return mixed
     */
    private function runAsyncTask(
        $task,
        string $method,
        array $params,
        string $logid,
        int $spanid,
        string $name,
        string $type
    ) {
        $taskParentClass = get_parent_class($task);
        $this->beforeTask($logid, $spanid, $name, $method, $type, $taskParentClass);
        $result = PhpHelper::call([$task, $method], $params);
        $this->afterTask($logid, $spanid, $name, $method, $type, $taskParentClass);

        return $result;
    }

    /**
     * @param object $task
     */
    private function runCoTask(
        $task,
        string $method,
        array $params,
        string $logid,
        int $spanid,
        string $name,
        string $type
    ): bool {
        return Coroutine::create(function () use ($task, $method, $params, $logid, $spanid, $name, $type) {
            $taskParentClass = get_parent_class($task);
            $this->beforeTask($logid, $spanid, $name, $method, $type, $taskParentClass);
            PhpHelper::call([$task, $method], $params);
            $this->afterTask($logid, $spanid, $name, $method, $type, $taskParentClass);
        });
    }

    /**
     * Trigger before_task event.
     */
    private function beforeTask(
        string $logid,
        int $spanid,
        string $name,
        string $method,
        string $type,
        string $taskClass
    ) {
        $event = new BeforeTaskEvent(TaskEvent::BEFORE_TASK, $logid, $spanid, $name, $method, $type, $taskClass);
        App::trigger($event);
    }

    /**
     * Trigger resource_release and after_task events.
     */
    private function afterTask(
        string $logid,
        int $spanid,
        string $name,
        string $method,
        string $type,
        string $taskClass
    ) {
        // Release system resources.
        App::trigger(AppEvent::RESOURCE_RELEASE);

        // Trigger after task event.
        $event = new AfterTaskEvent(TaskEvent::AFTER_TASK, $logid, $spanid, $name, $method, $type, $taskClass);
        App::trigger($event);
    }
}