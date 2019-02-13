<?php

namespace SwoftTest\WebSocket\Server\Fixture\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class ChatController
 *
 * @WsController("chat", module="chat")
 */
class ChatController
{
    /**
     * @MessageMapping()
     */
    public function send(): void
    {

    }

    /**
     * @MessageMapping()
     */
    public function notify(): void
    {

    }
}
