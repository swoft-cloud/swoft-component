<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Sg\Event\Listeners;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\AppEvent;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Worker start listener
 *
 * @Listener(AppEvent::WORKER_START)
 */
class WorkerStartListener implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     * @throws \Swoft\Exception\InvalidArgumentException
     */
    public function handle(EventInterface $event)
    {
        \provider()->select()->registerService();
    }
}
