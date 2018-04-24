<?php

namespace Swoft\Http\Server\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Core\RequestContext;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Event\HttpServerEvent;

/**
 * Class AfterRequestListener
 * @Listener(HttpServerEvent::AFTER_REQUEST)
 *
 * @package Swoft\Http\Server\Listener
 */
class AfterRequestListener implements EventHandlerInterface
{

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        RequestContext::getDefer()->run();
    }
}