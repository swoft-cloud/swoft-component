<?php declare(strict_types=1);

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
        $this->assertSame('>user1 >user2 [CORE] user2> user1>', $resp->getData());
    }
}
