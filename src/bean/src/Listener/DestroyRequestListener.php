<?php declare(strict_types=1);


namespace Swoft\Bean\Listener;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Bean\BeanEvent;

/**
 * Class DestroyRequestListener
 *
 * @since 2.0
 *
 * @Listener(BeanEvent::DESTROY_REQUEST)
 */
class DestroyRequestListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
    }
}