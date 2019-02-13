<?php

namespace SwoftTest\WebSocket\Server\Fixture\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class UserController
 * @WsController(prefix="user", module="chat")
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
