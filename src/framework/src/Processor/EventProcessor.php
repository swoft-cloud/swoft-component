<?php

namespace Swoft\Processor;

use Swoft\Event\Annotation\Parser\ListenerParser;
use Swoft\Event\Annotation\Parser\SubscriberParser;
use Swoft\Event\Manager\EventManager;
use Swoft\Log\Helper\CLog;
use Swoft\SwoftEvent;

/**
 * Event processor
 * @since 2.0
 */
class EventProcessor extends Processor
{
    /**
     * Handle event register
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(): bool
    {
        if (!$this->application->beforeEvent()) {
            CLog::warning('Stop event processor by beforeEvent return false');
            return false;
        }

        /** @var EventManager $em */
        $em = \bean('eventManager');

        $count1 = ListenerParser::addListeners($em);
        $count2 = SubscriberParser::addSubscribers($em);

        CLog::info('Event manager initialized(%d listener, %d subscriber)', $count1, $count2);

        // Trigger a app init event
        \Swoft::trigger(SwoftEvent::APP_INIT_COMPLETE);

        return $this->application->afterEvent();
    }
}