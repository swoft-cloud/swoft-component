<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Server;
use Swoft\Server\ServerEvent;
use function implode;

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
        $server = $event->getTarget();

        CLog::info('Server extra info: pidFile <info>%s</info>', $server->getPidFile());
        CLog::info("Registered swoole events:\n <info>%s</info>", implode(', ', $event->getParam(0)));
    }
}
