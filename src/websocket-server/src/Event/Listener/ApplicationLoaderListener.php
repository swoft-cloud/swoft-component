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

namespace Swoft\WebSocket\Server\Event\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\AppEvent;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\WebSocket\Server\Bean\Collector\WebSocketCollector;

/**
 * Class ApplicationLoaderListener
 * @package Swoft\WebSocket\Server\Event\Listener
 * @Listener(AppEvent::APPLICATION_LOADER)
 */
class ApplicationLoaderListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        /* @var \Swoft\WebSocket\Server\Router\HandlerMapping $router */
        $router = \bean('wsRouter');
        $router->registerRoutes(WebSocketCollector::getCollector());
    }
}
