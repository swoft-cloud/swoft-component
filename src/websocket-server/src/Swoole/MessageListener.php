<?php

namespace Swoft\WebSocket\Server\Swoole;


use Co\Websocket\Frame;
use Co\Websocket\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\MessageInterface;

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
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.
    }
}
