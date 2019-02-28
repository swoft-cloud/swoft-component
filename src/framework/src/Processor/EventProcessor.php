<?php

namespace Swoft\Processor;

use Swoft\Event\Annotation\Parser\ListenerParser;
use Swoft\Event\Annotation\Parser\SubscriberParser;
use Swoft\Event\Manager\EventManager;
use Swoft\Helper\CLog;

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

        ListenerParser::addListeners($em);
        SubscriberParser::addSubscribers($em);

        return $this->application->afterEvent();
    }
}