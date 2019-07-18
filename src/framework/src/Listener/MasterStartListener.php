<?php declare(strict_types=1);

namespace Swoft\Listener;

use Swoft\Console\Console;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\Event\ServerStartEvent;
use Swoole\Process;
use Swoft\Server\SwooleEvent;

/**
 * Class MasterStartListener
 *
 * @Listener(event=SwooleEvent::START)
 */
class MasterStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface|ServerStartEvent $event
     */
    public function handle(EventInterface $event): void
    {
        // Listen signal: Ctrl+C (SIGINT = 2)
        $server = $event->coServer;
        Process::signal(2, function () use ($server) {
            Console::colored("\nStop server by CTRL+C");
            $server->shutdown();
        });
    }
}
