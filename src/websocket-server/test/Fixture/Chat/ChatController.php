<?php

namespace SwoftTest\WebSocket\Server\Fixture\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use SwoftTest\WebSocket\Server\Fixture\ChatModule;

/**
 * Class ChatController
 *
 * @WsController("chat", module=ChatModule::class)
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
