<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Event\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Process\Event\ProcessEvent;

/**
 * After process listener
 *
 * @Listener(ProcessEvent::AFTER_PROCESS)
 */
class AfterProcessListener implements EventHandlerInterface
{
    /**
     * Event callback
     *
     * @param EventInterface $event Event object
     * @return void
     */
    public function handle(EventInterface $event)
    {
        // init log
        App::getLogger()->appendNoticeLog(true);
    }
}
