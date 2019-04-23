<?php declare(strict_types=1);


namespace Swoft\Task\Listener;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\SwoftEvent;
use Swoft\Task\TaskEvent;

/**
 * Class AfterFinishListener
 *
 * @since 2.0
 *
 * @Listener(event=TaskEvent::AFTER_FINISH)
 */
class AfterFinishListener implements EventHandlerInterface
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