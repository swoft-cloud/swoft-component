<?php declare(strict_types=1);


namespace Swoft\Bean\Listener;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Bean\BeanEvent;

/**
 * Class InitRequestListener
 *
 * @since 2.0
 *
 * @Listener(BeanEvent::INIT_REQUEST)
 */
class InitRequestListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
    }
}