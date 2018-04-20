<?php

namespace Swoft\Process\Event\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventInterface;
use Swoft\Event\EventHandlerInterface;
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
