<?php

namespace Swoft\Task;

use Swoft\App;
use Swoft\Pipe\PipeMessage;
use Swoft\Pipe\PipeMessageInterface;
use Swoft\Task\Exception\TaskException;
use Swoft\Task\Helper\TaskHelper;
use function bean;
use function in_array;
use function sprintf;

class Task
{
    /**
     * Coroutine task
     */
    const TYPE_CO = 'co';

    /**
     * Async task
     */
    const TYPE_ASYNC = 'async';

    /**
     * Deliver a coroutine or async task
     *
     * @return bool|array|int
     * @throws TaskException
     */
    public static function deliver(
        string $taskName,
        string $methodName,
        array $params = [],
        string $type = self::TYPE_CO,
        int $timeout = 3
    ) {
        if (! in_array($type, [static::TYPE_CO, static::TYPE_ASYNC], false)) {
            throw new TaskException('Invalid task type.');
        }
        $data = TaskHelper::pack($taskName, $methodName, $params, $type);
        if (! App::isWorkerStatus() && ! App::isCoContext()) {
            return static::deliverByQueue($data);
        }

        if (! App::isWorkerStatus() && App::isCoContext()) {
            throw new TaskException('Deliver in non-worker environment, please deliver the task via HTTP request.');
        }

        $server = App::$server->getServer();
        switch ($type) {
            case static::TYPE_CO:
                $tasks[0] = $data;
                $profileKey = 'task' . '.' . $taskName . '.' . $methodName;
                App::profileStart($profileKey);
                $result = $server->taskCo($tasks, $timeout);
                App::profileEnd($profileKey);
                return $result;
                break;
            case static::TYPE_ASYNC:
            default:
                // Deliver async task
                return $server->task($data);
                break;
        }
    }

    private static function deliverByQueue(string $data): bool
    {
        $queueTask = bean(QueueTask::class);
        return $queueTask->deliver($data);
    }

    /**
     * Deliver task by process
     */
    public static function deliverByProcess(
        string $taskName,
        string $methodName,
        array $params = [],
        int $timeout = 3,
        int $workerId = 0,
        string $type = self::TYPE_ASYNC
    ): bool {
        /* @var PipeMessageInterface $pipeMessage */
        $pipeMessage = bean(PipeMessage::class);
        $message = $pipeMessage->pack(PipeMessage::MESSAGE_TYPE_TASK, [
            'name' => $taskName,
            'method' => $methodName,
            'params' => $params,
            'timeout' => $timeout,
            'type' => $type,
        ]);
        return App::$server->getServer()->sendMessage($message, $workerId);
    }

    /**
     * Deliver multiple asynchronous tasks
     *
     * @param array $tasks
     *  <pre>
     *  $task = [
     *      'name'   => $taskName,
     *      'method' => $methodName,
     *      'params' => $params,
     *      'type'   => $type
     *  ];
     *  </pre>
     */
    public static function async(array $tasks): array
    {
        $server = App::$server->getServer();

        $result = [];
        foreach ($tasks as $task) {
            if (! isset($task['type']) || ! isset($task['name']) || ! isset($task['method']) || ! isset($task['params'])) {
                App::error(sprintf('Task %s format error.', $task['name'] ?? '[UNKNOWN]'));
                continue;
            }

            if ($task['type'] !== static::TYPE_ASYNC) {
                App::error(sprintf('Task %s is not a asynchronous task.', $task['name']));
                continue;
            }

            $data = TaskHelper::pack($task['name'], $task['method'], $task['params'], $task['type']);
            $result[] = $server->task($data);
        }

        return $result;
    }

    /**
     * @deprecated Use co() method instead, will be remove in swoft/task v1.1.
     */
    public static function cor(array $tasks): array
    {
        return static::co($tasks);
    }

    /**
     * Deliver multiple coroutine tasks
     *
     * @param array $tasks
     *  <pre>
     *  $tasks = [
     *      'name'   => $taskName,
     *      'method' => $methodName,
     *      'params' => $params,
     *      'type'   => $type
     *  ];
     *  </pre>
     */
    public static function co(array $tasks): array
    {
        $taskCos = [];
        foreach ($tasks as $task) {
            if (! isset($task['type']) || ! isset($task['name']) || ! isset($task['method']) || ! isset($task['params'])) {
                App::error(sprintf('Task %s format error.', $task['name'] ?? '[UNKNOWN]'));
                continue;
            }

            $type = $task['type'];
            if ($type !== static::TYPE_CO) {
                App::error(sprintf('Task %s is not a coroutine task.', $task['name']));
                continue;
            }

            $taskCos[] = TaskHelper::pack($task['name'], $task['method'], $task['params'], $task['type']);
        }

        $result = [];
        if (! empty($taskCos)) {
            $result = App::$server->getServer()->taskCo($tasks);
        }

        return $result;
    }

}
