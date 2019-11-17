<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Testing\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class UserController
 * @WsController(prefix="user")
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
