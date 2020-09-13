<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
