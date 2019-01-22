<?php

namespace Swoft\Processor;

use App\Model\Logic\DemoLogic;
use Swoft\Bean\BeanFactory;

/**
 * Event processor
 */
class EventProcessor extends Processor
{
    /**
     * Handle event
     */
    public function handle(): bool
    {
        if (!$this->application->beforeEvent()) {
            return false;
        }
//        echo 'event' . PHP_EOL;

        /* @var DemoLogic $logic*/
        $logic = bean(DemoLogic::class);
        
        $logic->getData()->getDao();

//        var_dump(config('db.host'));
//        var_dump(config('db'));
//        var_dump(config('name'));
        return $this->application->afterEvent();
    }
}