<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Listener;

use Swoft\Bean\BeanFactory;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\SwoftEvent;
use Swoft\Task\Router\Router;
use Swoft\Task\Router\RouteRegister;

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
     */
    public function handle(EventInterface $event): void
    {
        /* @var Router $router */
        $router = BeanFactory::getBean('taskRouter');

        RouteRegister::registerRoutes($router);
    }
}
