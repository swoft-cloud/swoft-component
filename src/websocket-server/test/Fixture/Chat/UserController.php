<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-13
 * Time: 13:28
 */

namespace SwoftTest\WebSocket\Server\Fixture\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class UserController
 * @WsController()
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