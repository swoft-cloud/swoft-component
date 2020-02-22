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
     * @return string
     */
    public function send(): string
    {
        return __METHOD__;
    }

    /**
     * @MessageMapping()
     */
    public function notify(): void
    {
    }

}
