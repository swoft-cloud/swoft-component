<?php

namespace SwoftTest\Db\Testing\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Db\Event\ModelEvent;

/**
 * Model after save handler
 *
 * @Listener(ModelEvent::AFTER_SAVE)
 */
class AfterSaveListener implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $model = $event->getModel();
        if (method_exists($model, 'setAge') && method_exists($model, 'getAge')) {
            $model->setAge($model->getAge() + 1);
        }
    }
}