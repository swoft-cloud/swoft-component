<?php

namespace Swoft\Task\Crontab;

use Swoft\Bean\Annotation\Bean;
use Swoft\Task\Bean\Collector\TaskCollector;

/**
 * Crontab
 *
 * @Bean()
 */
class Crontab implements CronInterface
{
    /**
     * Initialize
     */
    public function initialize()
    {

    }

    /**
     * Consume task
     */
    public function consume()
    {
        swoole_timer_tick(1 * 1000, function () {
            CronManager::getCron()->consume();
        });
    }

    /**
     * @param bool $isFirst
     */
    public function produce(bool $isFirst = false)
    {
        CronManager::getCron()->produce(true);
        swoole_timer_tick(60 * 1000, function () {
            CronManager::getCron()->produce();
        });
    }

    /**
     * @return array
     */
    public function getTaskList()
    {
        $collector = TaskCollector::getCollector();
        $tasks     = $collector['crons']?? [];

        return $tasks;
    }
}