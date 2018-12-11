<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
