<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Testing\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use Swoft\WebSocket\Server\Message\Message;

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

    /**
     * @MessageMapping()
     * @param Message $message
     *
     * @return Message
     */
    public function reply(Message $message): Message
    {
        $msg = $message->getDataString();

        return $message->newWithData('RECV: ' . $msg);
    }
}
