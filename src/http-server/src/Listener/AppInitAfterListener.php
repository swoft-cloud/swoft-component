<?php

namespace Swoft\Http\Server\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Router\Router;
use Swoft\Http\Server\Router\RouteRegister;
use Swoft\SwoftEvent;

/**
 * Class AppInitAfterListener
 * @since 2.0
 *
 * @Listener(SwoftEvent::APP_INIT_AFTER)
 */
class AppInitAfterListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(EventInterface $event): void
    {
        /** @var Router $router Register HTTP routes*/
        $router = \bean('httpRouter');

        RouteRegister::registerRoutes($router);
    }
}