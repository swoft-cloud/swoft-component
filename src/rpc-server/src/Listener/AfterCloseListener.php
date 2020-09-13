<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\SwoftEvent;
use Swoft\Rpc\Server\ServiceServerEvent;

/**
 * Class AfterCloseListener
 *
 * @since 2.0
 *
 * @Listener(event=ServiceServerEvent::AFTER_CLOSE)
 */
class AfterCloseListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        // Defer
        Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

        // Destroy
        Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);
    }
}
