<?php

namespace Swoft\Listener;

use Swoft\Console\Console;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\Event\ServerStartEvent;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class ManagerStartListener
 * @since 2.0
 * @Listener(SwooleEvent::MANAGER_START)
 */
class ManagerStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface|ServerStartEvent $event
     */
    public function handle(EventInterface $event): void
    {
        $server = $event->coServer;

        Console::writef(
            'Server start success (Master PID: %d, Manager PID: %d)',
            $server->master_pid,
            $server->manager_pid
        );
    }
}
