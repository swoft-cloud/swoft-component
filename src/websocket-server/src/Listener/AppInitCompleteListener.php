<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\SwoftEvent;
use Swoft\WebSocket\Server\Router\Router;
use Swoft\WebSocket\Server\Router\RouteRegister;
use Swoft\WebSocket\Server\WsServerBean;

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
     */
    public function handle(EventInterface $event): void
    {
        // Register webSocket routes

        /** @var Router $router */
        $router = Swoft::getSingleton(WsServerBean::ROUTER);

        RouteRegister::registerTo($router);

        CLog::info('WebSocket server route registered(module %d, message command %d)', $router->getModuleCount(), $router->getCounter());
    }
}
