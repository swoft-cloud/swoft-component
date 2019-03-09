<?php declare(strict_types=1);


namespace Swoft\Bean\Listener;


use Swoft\Bean\BeanFactory;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Bean\BeanEvent;
use Swoft\Helper\Log;

/**
 * Class DestroyRequestListener
 *
 * @since 2.0
 *
 * @Listener(BeanEvent::DESTROY_REQUEST)
 */
class DestroyRequestListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $id = (int)$event->getParam(0, 0);
        if ($id === 0) {
            return;
        }

        BeanFactory::destroyRequest($id);
    }
}