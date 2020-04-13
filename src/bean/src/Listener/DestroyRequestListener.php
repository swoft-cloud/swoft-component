<?php declare(strict_types=1);

namespace Swoft\Bean\Listener;

use Swoft\Bean\BeanEvent;
use Swoft\Bean\BeanFactory;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

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
        $id = $event->getParam(0, '');
        if (!$id) {
            return;
        }

        BeanFactory::destroyRequest((string)$id);
    }
}
