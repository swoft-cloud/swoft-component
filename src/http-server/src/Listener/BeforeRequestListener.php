<?php

namespace Swoft\Http\Server\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Core\RequestContext;
use Swoft\Defer\Defer;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Event\HttpServerEvent;

/**
 * Class BeforeRequestListener
 * @Listener(HttpServerEvent::BEFORE_REQUEST)
 *
 * @package Swoft\Http\Server\Listener
 */
class BeforeRequestListener implements EventHandlerInterface
{

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        RequestContext::setDefer(new Defer());
    }

}