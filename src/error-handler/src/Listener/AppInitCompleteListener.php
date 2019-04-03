<?php

namespace Swoft\ErrorHandler\Bootstrap\Listener;

use Swoft\ErrorHandler\ErrorHandlerChain;
use Swoft\ErrorHandler\HandlerRegister;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Helper\CLog;
use Swoft\SwoftEvent;

/**
 * Class WorkerStartListener
 *
 * @since 2.0
 * @Listener(SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(EventInterface $event): void
    {
        $chain = \Swoft::getBean(ErrorHandlerChain::class);

        HandlerRegister::register($chain);

        CLog::info('Error handler init complete(%d handler)', $chain->count());
    }
}
