<?php

namespace SwoftTest\Db\Testing\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Db\Event\ModelEvent;

/**
 * Model before save handler
 *
 * @Listener(ModelEvent::BEFORE_SAVE)
 */
class BeforeSaveListener implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $model = $event->getModel();
        if (method_exists($model, 'getDesc') && is_null($model->getDesc())) {
            $model->setDesc('Set by beforeSaveListener');
        }
    }
}