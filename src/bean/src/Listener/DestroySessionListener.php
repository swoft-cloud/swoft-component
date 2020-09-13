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
 * Class DestroySessionListener
 *
 * @since 2.0
 *
 * @Listener(BeanEvent::DESTROY_SESSION)
 */
class DestroySessionListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $id = (string)$event->getParam(0, '');
        if (empty($id)) {
            return;
        }

        BeanFactory::destroySession($id);
    }
}
