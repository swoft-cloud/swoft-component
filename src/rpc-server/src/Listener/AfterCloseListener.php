<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Listener;


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
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(EventInterface $event): void
    {
        // Defer
        \Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

        // Destroy
        \Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);
    }
}