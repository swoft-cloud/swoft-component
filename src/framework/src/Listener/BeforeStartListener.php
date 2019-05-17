<?php

namespace Swoft\Listener;

use function implode;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Server;
use Swoft\Server\ServerEvent;

/**
 * Class BeforeStartListener
 * @Listener(ServerEvent::BEFORE_START)
 */
class BeforeStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var Server $server */
        // $server = $event->getTarget();

        CLog::info("Registered swoole events:\n <info>%s</info>", implode(', ', $event->getParam(0)));
    }
}
