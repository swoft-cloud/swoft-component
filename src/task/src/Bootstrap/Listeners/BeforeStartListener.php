<?php

namespace Swoft\Task\Bootstrap\Listeners;

use Swoft\Bean\Annotation\BeforeStart;
use Swoft\Bootstrap\Listeners\Interfaces\BeforeStartInterface;
use Swoft\Bootstrap\Server\AbstractServer;
use Swoft\Task\Crontab\CronManager;

/**
 * The listener of before start
 * @BeforeStart()
 */
class BeforeStartListener implements BeforeStartInterface
{
    /**
     * @param AbstractServer $server
     */
    public function onBeforeStart(AbstractServer $server)
    {
        CronManager::init();
    }
}
