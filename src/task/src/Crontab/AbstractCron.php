<?php

namespace Swoft\Task\Crontab;

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

    }

    /**
     * @return bool
     */
    protected function isCron(): bool
    {
        //        /** @var array[] $settings */
        //        $settings = App::getAppProperties()->get('server');
        //        $settings = $settings['crontab'];
        //        $cron     = $settings['cron']?? CronManager::CRON_TABLE;
        //        $isCron   = env('cron', CronManager::CRON_TABLE) == $cron;

        return true;
    }
}