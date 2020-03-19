<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit\Message;

use PHPUnit\Framework\TestCase;
use Swoft\WebSocket\Server\Exception\WsMiddlewareException;
use Swoft\WebSocket\Server\Message\MessageHandler;
use Swoft\WebSocket\Server\Message\Request;
use SwoftTest\WebSocket\Server\Testing\Middleware\CoreMiddleware;
use SwoftTest\WebSocket\Server\Testing\Middleware\User1Middleware;
use SwoftTest\WebSocket\Server\Testing\Middleware\User2Middleware;
use Swoole\WebSocket\Frame;

/**
 * Class MessageHandlerTest
 */
class MessageHandlerTest extends TestCase
{
    /**
     * @throws WsMiddlewareException
     */
    public function testRun(): void
    {
        $coreMdl = new CoreMiddleware();

        $mc = MessageHandler::new($coreMdl);
        $mc->middle(new User1Middleware());
        $mc->add(new User2Middleware());

        $req  = Request::new(new Frame());
        $resp = $mc->run($req);

        $this->assertSame(100, $resp->getSender());
        $this->assertSame('>USER1 >USER2 [CORE] USER2> USER1>', $resp->getData());
    }
}
