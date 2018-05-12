<?php

namespace Swoft\Task\Bootstrap;

use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\JsonHelper;
use Swoft\Log\Log;
use Swoft\Memory\TableBuilder;
use Swoft\Task\Bean\Collector\TaskCollector;
use Swoft\Task\Crontab\AbstractCron;
use Swoft\Task\Exception\CronException;
use Swoft\Task\Helper\CronHelper;
use Swoft\Task\Helper\Express;
use Swoft\Task\Task;
use Swoole\Table;

/**
 * @Bean()
 */
class CronTable extends AbstractCron
{
    /**
     * Task table name
     */
    const TABLE_TASK = 'task';

    /**
     * Queue table name
     */
    const TABLE_QUEUE = 'queue';

    /**
     * @var int
     */
    private $lastTime;

    /**
     * @var int
     */
    private $firstTime = 60 * 3;

    /**
     * @var int
     */
    private $intervalTime = 60;

    /**
     * @var bool
     */
    private $isInitTaskTable = false;

    /**
     * @var array
     */
    private $clearQueueKeys = [];

    /**
     * @var array
     */
    private $taskColumns = [
            'express' => [Table::TYPE_STRING, 100],
            'class'   => [Table::TYPE_STRING, 255],
            'method'  => [Table::TYPE_STRING, 255],
            'ctime'   => [Table::TYPE_STRING, 11],
        ];

    /**
     * @var array
     */
    private $queueColumns = [
            'tasks' => [Table::TYPE_STRING, 500],
            'ctime' => [Table::TYPE_STRING, 11],
        ];

    /**
     * Init
     */
    public function initialize()
    {
        if (CronHelper::isCronable() && $this->isCron()) {
            $this->createTable();
        }
    }

    /**
     * Produce task
     *
     * @param bool $isFirst
     */
    public function produce(bool $isFirst = false)
    {
        $this->initTaskTable();

        $taskTable = TableBuilder::get(self::TABLE_TASK);
        if ($taskTable->count() <= 0) {
            Log::trace('No execution of the task!');
            return;
        }

        $time    = ($this->lastTime === null) ? time() : $this->lastTime;
        $endTime = $isFirst ? $time + $this->firstTime : $time + $this->intervalTime;

        // Produce tasks
        $tasks = $taskTable->getTable();
        for (; $time < $endTime; $time++) {
            $result = $this->produceTask($tasks, $time);
            if (!$result) {
                Log::error(sprintf('Produce task error! time=%d', $time));
            }
            $this->lastTime = $endTime;
        }

        Log::trace(sprintf('Produce task completion! lastTime=%d', $this->lastTime));
    }

    /**
     * Consume task
     */
    public function consume()
    {
        // Clear keys
        $this->clearQueueKeys();

        $key        = time();
        $queueTable = TableBuilder::get(self::TABLE_QUEUE);
        $tasks      = $queueTable->get($key, 'tasks');

        if ($tasks === false || empty($tasks)) {
            Log::trace(sprintf('No execution of the task! time=%d', $key));

            return;
        }

        $tasks = JsonHelper::decode($tasks, true);
        foreach ($tasks as $task) {
            $result = Task::deliverByProcess($task['class'], $task['method']);
            if ($result) {
                Log::trace(sprintf('Deliver task success! class=%s, method=%s, time=%d', $task['class'], $task['method'], $key));
                continue;
            }

            Log::error(sprintf('Deliver task fail! class=%s, method=%s, time=%d', $task['class'], $task['method'], $key));
        }

        $result = $queueTable->del($key);
        if ($result) {
            Log::trace(sprintf('Consume delete key success, time=%d', $key));

            return;
        }

        $this->clearQueueKeys[] = $key;
        Log::trace(sprintf('Consume delete key fail, time=%d', $key));
    }

    /**
     * Create table
     */
    private function createTable()
    {
        $isCreateTask  = TableBuilder::create(self::TABLE_TASK, $this->taskCount, $this->taskColumns);
        $isCreateQueue = TableBuilder::create(self::TABLE_QUEUE, $this->queueSize, $this->queueColumns);
        if (!$isCreateTask || !$isCreateQueue) {
            throw new CronException('Crontab table initialization failure');
        }
    }

    /**
     * Init task table
     */
    private function initTaskTable()
    {
        if ($this->isInitTaskTable) {
            return;
        }

        // Task infos
        $collector = TaskCollector::getCollector();
        $tasks     = $collector['crons']?? [];
        $taskTable = TableBuilder::get(self::TABLE_TASK);

        foreach ($tasks as $index => $task) {
            $taskTableCount = $taskTable->count();
            if ($taskTableCount >= $this->taskCount) {
                continue;
            }

            $key = $this->getTableKey($task['cron'], $task['task'], $task['method']);
            if ($taskTable->exist($key)) {
                continue;
            }

            $data = [
                'express' => $task['cron'],
                'class'   => $task['task'],
                'method'  => $task['method'],
                'ctime'   => time(),
            ];

            $result = $taskTable->set($key, $data);
            if ($result) {
                Log::trace(sprintf('Load task success, info=%s', JsonHelper::encode($data, JSON_UNESCAPED_UNICODE)));
                continue;
            }
            Log::error(sprintf('Load task error, info=%s', JsonHelper::encode($data, JSON_UNESCAPED_UNICODE)));
        }

        $this->isInitTaskTable = true;
    }

    /**
     * @param mixed $tasks
     * @param int   $time
     *
     * @return bool
     */
    private function produceTask($tasks, int $time): bool
    {
        $runTasks = [];
        foreach ($tasks as $task) {
            $express = $task['express'];
            if (!Express::validateExpress($express, $time)) {
                continue;
            }

            $runTasks[] = [
                'class'  => $task['class'],
                'method' => $task['method'],
            ];
        }

        $queueTable = TableBuilder::get(self::TABLE_QUEUE);

        return $queueTable->set($time, ['tasks' => JsonHelper::encode($runTasks, JSON_UNESCAPED_UNICODE)]);
    }

    /**
     * Clear queue keys
     */
    private function clearQueueKeys()
    {
        if (empty($this->clearQueueKeys)) {
            return;
        }

        $queueTable = TableBuilder::get(self::TABLE_QUEUE);
        foreach ($this->clearQueueKeys as $key) {
            $queueTable->del($key);
        }
    }

    /**
     * @param string $rule
     * @param string $taskClass
     * @param string $taskMethod
     * @param string $min
     * @param string $sec
     *
     * @return string
     */
    private function getTableKey(string $rule, string $taskClass, string $taskMethod, $min = '', $sec = ''): string
    {
        return md5($rule . $taskClass . $taskMethod . $min . $sec);
    }
}