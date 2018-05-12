<?php

namespace Swoft\Task\Crontab;

use Swoft\App;

/**
 * AbstractCron
 */
abstract class AbstractCron implements CronInterface
{
    /**
     * @var int
     */
    protected $taskCount = 1024;

    /**
     * @var int
     */
    protected $queueSize = 1024;

    /**
     * @var string
     */
    protected $cron = CronManager::CRON_TABLE;

    /**
     * Initialize
     */
    public function initialize()
    {
        /** @var array[] $settings */
        $settings = App::getAppProperties()->get('server');
        $settings = $settings['crontab'];
        var_dump($settings);
        $this->taskCount = (int)$settings['task_count']?? $this->taskCount;
        $this->queueSize = (int)$settings['task_queue']?? $this->taskCount;
    }

    /**
     * @return bool
     */
    protected function isCron(): bool
    {
        /** @var array[] $settings */
        $settings = App::getAppProperties()->get('server');
        $settings = $settings['crontab'];
        $cron     = $settings['cron']?? CronManager::CRON_TABLE;

        var_dump($settings);
        return $this->cron == $cron;
    }
}