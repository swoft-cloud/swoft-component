<?php declare(strict_types=1);


namespace Swoft\Redis\Listener;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\SwoftEvent;

/**
 * Class CoroutineDeferListener
 *
 * @since 2.0
 *
 * @Listener(event=SwoftEvent::COROUTINE_DEFER)
 */
class CoroutineDeferListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {

    }
}