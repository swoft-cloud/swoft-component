<?php

namespace SwoftTest\Db\Testing\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Db\Event\ModelEvent;

/**
 * Model before save handler
 *
 * @Listener(ModelEvent::BEFORE_UPDATE)
 */
class BeforeUpdateListener implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $model = $event->getModel();
        if (method_exists($model, 'getDesc') && method_exists($model, 'setDesc')) {
            if ('Set by beforeSaveListener' == $model->getDesc()) {
                $model->setDesc('Update by beforeUpdateLinstener');
            }
        }
    }
}