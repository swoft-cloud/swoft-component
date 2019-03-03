<?php declare(strict_types=1);


namespace Swoft\Bean\Listener;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Bean\BeanEvent;

/**
 * Class DestroySessionListener
 *
 * @since 2.0
 *
 * @Listener(BeanEvent::DESTROY_SESSION)
 */
class DestroySessionListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
    }
}