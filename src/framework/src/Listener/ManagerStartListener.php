<?php

namespace Swoft\Listener;

use Swoft\Console\Console;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class ManagerStartListener
 * @since 2.o
 * @Listener(SwooleEvent::MANAGER_START)
 */
class ManagerStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var \Swoft\Server\Server $server */
        $server = $event->getParam(0);
        $pidMap = $server->getPidMap();

        Console::writef('Server start success (Master PID: %d, Manager PID: %d)', $pidMap['master'], $pidMap['manager']);
    }
}
