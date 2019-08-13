<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoft\WebSocket\Server\WsDispatcher;
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
    public function testHandshake(): void
    {
        /** @var WsDispatcher $dp */
        $dp = bean('wsDispatcher');

        $this->assertNotEmpty($dp);

        $conn = $this->newConnection(10, '/ws-test/chat');

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
    }
}
