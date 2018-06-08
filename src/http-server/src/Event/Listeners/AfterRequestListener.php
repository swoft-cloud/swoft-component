<?php

namespace Swoft\Http\Server\Event\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Core\RequestContext;
use Swoft\Defer\Defer;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Event\HttpServerEvent;

/**
 * @Listener(HttpServerEvent::AFTER_REQUEST)
 */
class AfterRequestListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        App::getLogger()->appendNoticeLog();
        if (RequestContext::get('defer') instanceof Defer) {
            /** @var Defer $defer */
            $defer = RequestContext::get('defer');
            $defer->run();
        }
        RequestContext::destroy();
    }
}
