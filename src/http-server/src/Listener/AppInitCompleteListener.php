<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Exception\HttpServerException;
use Swoft\Http\Server\HttpServerEvent;
use Swoft\Http\Server\Middleware\MiddlewareRegister;
use Swoft\Http\Server\Router\Router;
use Swoft\Http\Server\Router\RouteRegister;
use Swoft\SwoftEvent;

/**
 * Class AppInitCompleteListener
 *
 * @since 2.0
 *
 * @Listener(SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws HttpServerException
     */
    public function handle(EventInterface $event): void
    {
        /** @var Router $router Register HTTP routes */
        $router = Swoft::getBean('httpRouter');

        // Register route
        RouteRegister::registerRoutes($router);

        // Register middleware
        MiddlewareRegister::register();

        Swoft::trigger(HttpServerEvent::ROUTE_REGISTERED, $router);
    }
}
