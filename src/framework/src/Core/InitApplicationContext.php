<?php

namespace Swoft\Core;

use Swoft\App;
use Swoft\Bean\Collector\ListenerCollector;
use Swoft\Event\AppEvent;

class InitApplicationContext
{

    public function init()
    {
        $this->registerListeners();
        $this->applicationLoader();
    }

    private function registerListeners()
    {
        ApplicationContext::registerListeners(ListenerCollector::getCollector());
    }

    private function applicationLoader()
    {
        App::trigger(AppEvent::APPLICATION_LOADER, null);
    }
}
