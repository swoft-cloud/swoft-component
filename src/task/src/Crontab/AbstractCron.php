<?php

namespace Swoft\Task\Crontab;

use Swoft\App;

/**
 * AbstractCron
 */
abstract class AbstractCron implements CronInterface
{
    const NORMAL = 0;

    const FINISH = 1;

    const START = 2;

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
     * Init
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