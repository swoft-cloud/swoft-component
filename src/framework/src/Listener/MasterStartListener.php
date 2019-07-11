<?php declare(strict_types=1);

namespace Swoft\Listener;

use Swoft\Console\Console;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\Event\ServerStartEvent;
use Swoft\Server\Helper\ServerHelper;
use Swoole\Process;

/**
 * Class MasterStartListener
 *
 * @Listener()
 */
class MasterStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface|ServerStartEvent $event
     */
    public function handle(EventInterface $event): void
    {
        // Not listen ctrl+c on 4.4
        if (ServerHelper::isGteSwoole44()) {
            return;
        }

        $server = $event->coServer;

        // Listen signal: Ctrl+C (SIGINT = 2)
        Process::signal(2, function () use ($server) {
            Console::colored("\nStop server by CTRL+C");
            $server->shutdown();
        });
    }
}
