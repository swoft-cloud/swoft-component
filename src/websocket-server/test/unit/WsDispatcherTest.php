<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoft\WebSocket\Server\MessageParser\JsonParser;
use Swoft\WebSocket\Server\WsDispatcher;
use SwoftTest\WebSocket\Server\Testing\ChatModule;
use Throwable;
use function bean;
use function get_class;

/**
 * Class WsDispatcherTest
 *
 * @since 2.0
 */
class WsDispatcherTest extends WsServerTestCase
{
    /**
     * php run.php -c src/websocket-server/phpunit.xml --filter testHandshake
     *
     * @throws Throwable
     */
    public function testHandshake(): string
    {
        /** @var WsDispatcher $dp */
        $fd = 10;
        $dp = bean('wsDispatcher');

        $this->assertNotEmpty($dp);

        $conn = $this->newConnection($fd, '/ws-test/chat');

        /** @var Response $res */
        [$status, $res] = $dp->handshake($conn->getRequest(), $conn->getResponse());

        $this->assertTrue($status);
        $this->assertSame('in testing', (string)$res->getBody());
        $want = 'SwoftTest\WebSocket\Server\Testing\ChatModule::checkHandshake';
        $this->assertSame($want, $conn->get('handshake:/ws-test/chat'));

        $this->assertSame('/ws-test/chat', $conn->getMetaValue('path'));

        try {
            $conn->getRequest()->setUriPath('/not-exist-path');
            $dp->handshake($conn->getRequest(), $conn->getResponse());
        } catch (Throwable $e) {
            $this->assertSame(WsModuleRouteException::class, get_class($e));
            $this->assertSame('The requested websocket route path "/not-exist-path" is not exist!', $e->getMessage());
        }

        return (string)$fd;
    }

    /**
     * @depends testHandshake
     *
     * @param string $sid
     */
    public function testModuleInfo(string $sid): void
    {
        $this->assertTrue(Session::has($sid));
        $conn = Session::mustGet();
        $this->assertSame($conn, Session::mustGet($sid));

        $info = $conn->getModuleInfo();

        $this->assertArrayHasKey('path', $info);
        $this->assertSame('/ws-test/chat', $info['path']);

        $this->assertArrayHasKey('class', $info);
        $this->assertSame(ChatModule::class, $info['class']);

        $this->assertArrayHasKey('messageParser', $info);
        $this->assertSame(JsonParser::class, $info['messageParser']);
        $this->assertSame(JsonParser::class, $conn->getParserClass());

        $this->assertArrayHasKey('controllers', $info);
        $this->assertNotEmpty($info['controllers']);

        $this->rmConnection($sid);

        $this->assertEmpty($conn->getModuleInfo());
    }
}
