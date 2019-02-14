<?php

namespace SwoftTest\WebSocket\Server\Fixture\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use SwoftTest\WebSocket\Server\Fixture\ChatModule;

/**
 * Class UserController
 * @WsController(prefix="user", module=ChatModule::class)
 */
class UserController
{
    /**
     * @MessageMapping("login")
     */
    public function login(): void
    {

    }

    /**
     * @MessageMapping()
     */
    public function logout(): void
    {

    }
}
