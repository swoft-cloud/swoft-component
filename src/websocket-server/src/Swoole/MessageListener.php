<?php

namespace Swoft\WebSocket\Server\Swoole;


use Co\Websocket\Frame;
use Co\Websocket\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Server\Swoole\MessageInterface;
use Swoft\WebSocket\Server\Connections;
use Swoft\WebSocket\Server\WsContext;
use Swoft\WebSocket\Server\WsEvent;

/**
 * Class MessageListener
 *
 * @Bean("messageListener")
 *
 * @since 2.0
 */
class MessageListener implements MessageInterface
{
    /**
     * @param Server $server
     * @param Frame  $frame
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        $fd = $frame->fd;

        // init fd and coId mapping
        Connections::bindFd($fd);

        /** @var WsContext $conn */
        $ctx = \bean(WsContext::class);

        \Swoft::trigger(WsEvent::ON_MESSAGE, null, $server, $frame);

        \server()->log("received message: {$frame->data} from fd #{$fd}, co ID #" . Co::tid(), [], 'debug');

        /** @see Dispatcher::message() */
        \bean('wsDispatcher')->message($server, $frame);

        // delete coId from fd mapping
        Connections::unbindFd();
    }
}
