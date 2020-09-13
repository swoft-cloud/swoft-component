<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Listener;

use Swoft\Bean\BeanFactory;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Rpc\Server\Middleware\MiddlewareRegister;
use Swoft\Rpc\Server\Router\Router;
use Swoft\Rpc\Server\Router\RouteRegister;
use Swoft\SwoftEvent;

/**
 * Class AppInitCompleteListener
 *
 * @since 2.0
 *
 * @Listener(event=SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws \Swoft\Rpc\Server\Exception\RpcServerException
     */
    public function handle(EventInterface $event): void
    {
        /* @var Router $router */
        $router = BeanFactory::getBean('serviceRouter');

        // Register router
        RouteRegister::registerRoutes($router);

        // Register middleware
        MiddlewareRegister::register();
    }
}
