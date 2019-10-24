<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Testing\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use SwoftTest\WebSocket\Server\Testing\Middleware\User1Middleware;
use SwoftTest\WebSocket\Server\Testing\Middleware\User2Middleware;

/**
 * Class ChatController
 *
 * @WsController("chat", middlewares={User1Middleware::class})
 */
class ChatController
{
    /**
     * @MessageMapping(middlewares={User2Middleware::class})
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
