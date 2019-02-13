<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 17:25
 */

namespace SwoftTest\WebSocket\Server\Fixture\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class ChatController
 *
 * @WsController("chat")
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
