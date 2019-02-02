<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 18:51
 */

namespace Swoft\Http\Server\Event;

use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Router\Router;
use Swoft\Http\Server\Router\RoutesCollector;

/**
 * Class AppInitAfterListener
 * @package Swoft\Http\Server\Event
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
        // - register routes

        /** @var Router $router */
        $router = \bean('httpRouter');

        RoutesCollector::registerRoutes($router);
    }
}