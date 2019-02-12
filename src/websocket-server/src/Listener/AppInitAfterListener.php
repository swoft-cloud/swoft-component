<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 18:51
 */

namespace Swoft\WebSocket\Server\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\SwoftEvent;
use Swoft\WebSocket\Server\Annotation\Parser\WebSocketParser;
use Swoft\WebSocket\Server\Router\Router;

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
        // - register WS routes

        /** @var Router $router */
        $router = \bean('wsRouter');

        WebSocketParser::registerTo($router);
    }
}
