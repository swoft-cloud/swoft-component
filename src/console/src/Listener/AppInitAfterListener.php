<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 18:51
 */

namespace Swoft\Console\Listener;

use Swoft\Console\Bean\Parser\CommandParser;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Console\Router\Router;
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
        // - register console routes

        /** @var Router $router */
        $router = \bean('consoleRouter');

        CommandParser::registerTo($router);
    }
}
