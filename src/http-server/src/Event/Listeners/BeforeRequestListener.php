<?php

namespace Swoft\Http\Server\Event\Listeners;

use Swoft\Bean\Annotation\Listener;
use Swoft\Core\RequestContext;
use Swoft\Defer\Defer;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\Event\HttpServerEvent;

/**
 * @Listener(HttpServerEvent::BEFORE_REQUEST)
 */
class BeforeRequestListener implements EventHandlerInterface
{
    /**
     * Event callback
     *
     * @param EventInterface $event Event object
     */
    public function handle(EventInterface $event)
    {
        // header获取日志ID和spanid请求跨度ID
        $logId = RequestContext::getRequest()->getHeaderLine('logid');
        $spanId = RequestContext::getRequest()->getHeaderLine('spanid');

        if (!$logId) {
            $logId = uniqid('', false);
        }

        $uri = RequestContext::getRequest()->getUri();

        $contextData = [
            'logid'       => $logId,
            'spanid'      => $spanId ?: 0,
            'uri'         => $uri,
            'requestTime' => \microtime(true),
        ];

        RequestContext::setContextData($contextData);
        RequestContext::set('defer', new Defer());
    }
}
