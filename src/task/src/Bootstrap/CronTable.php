<?php

namespace Swoft\Task\Bootstrap;

use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Memory\TableBuilder;
use Swoft\Task\Bean\Collector\TaskCollector;
use Swoft\Task\Crontab\AbstractCron;
use Swoft\Task\Exception\CronException;
use Swoft\Task\Helper\CronHelper;
use Swoft\Task\Task;
use Swoole\Table;

/**
 * @Bean()
 */
class CronTable extends AbstractCron
{
    const TABLE_TASK = 'task';

    const TABLE_QUEUE = 'queue';

    /**
     * @var array
     */
    private $taskColumns
        = [
            'rule'       => [Table::TYPE_STRING, 100],
            'taskClass'  => [Table::TYPE_STRING, 255],
            'taskMethod' => [Table::TYPE_STRING, 255],
            'add_time'   => [Table::TYPE_STRING, 11],
        ];

    /**
     * @var array
     */
    private $queueColumns
        = [
            'taskClass'  => [Table::TYPE_STRING, 255],
            'taskMethod' => [Table::TYPE_STRING, 255],
            'minute'     => [Table::TYPE_STRING, 20],
            'sec'        => [Table::TYPE_STRING, 20],
            'runStatus'  => [Table::TYPE_INT, 4],
        ];

    /**
     * @var bool
     */
    private $isInitTaskTable = false;

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
     * Produce
     */
    public function produce()
    {
        $this->initTaskTable();

        $taskTable = TableBuilder::get(self::TABLE_TASK);
        if ($taskTable->count() <= 0) {
            return;
        }

        $time  = time();
        $tasks = $taskTable->getTable();
        foreach ($tasks as $id => $task) {
            $nextTasks = CronHelper::parse($task['rule'], $time);
            if (empty($nextTasks)) {
                continue;
            }

            $this->produceTask($task, $nextTasks);
        }
    }

    /**
     * Consume
     */
    public function consume()
    {
        $queueTable = TableBuilder::get(self::TABLE_QUEUE);
        $tasks      = $queueTable->getTable();

        $runTasks = [];
        foreach ($tasks as $key => $task) {
            if ($task['minute'] != date('YmdHi') || time() != $task['sec'] || $task['runStatus'] != self::NORMAL) {
                continue;
            }

            $runTasks[$key] = [
                'taskClass'  => $task['taskClass'],
                'taskMethod' => $task['taskMethod'],
            ];
        }

        // Run task
        foreach ($runTasks as $key => $runTask) {
            $queueTable->set($key, ['runStatus' => self::START]);
            Task::deliverByProcess($runTask['taskClass'], $runTask['taskMethod']);
            $queueTable->del($key);
        }
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
     * @param array $task
     * @param array $nextTasks
     */
    private function produceTask(array $task, array $nextTasks)
    {
        $min        = date('YmdHi');
        $sec        = strtotime(date('Y-m-d H:i'));
        $queueTable = TableBuilder::get(self::TABLE_QUEUE);

        foreach ($nextTasks as $time) {
            if ($queueTable->count() >= $this->queueSize) {
                continue;
            }

            $data = [
                'taskClass'  => $task['taskClass'],
                'taskMethod' => $task['taskMethod'],
                'minute'     => $min,
                'sec'        => $time + $sec,
                'runStatus'  => self::NORMAL,
            ];
            $key  = $this->getTableKey($task['rule'], $task['taskClass'], $task['taskMethod'], $min, $time + $sec);
            var_dump($time + $sec);
            $queueTable->set($key, $data);
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

        var_dump('isInitTaskTable');

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
                'rule'       => $task['cron'],
                'taskClass'  => $task['task'],
                'taskMethod' => $task['method'],
                'add_time'   => time(),
            ];
            $taskTable->set($key, $data);
        }

        $this->isInitTaskTable = true;
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