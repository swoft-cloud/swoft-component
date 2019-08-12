<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use function bean;
use function get_class;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoft\WebSocket\Server\WsDispatcher;
use Throwable;

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

        [$status, $res] = $dp->handshake($conn->getRequest(), $conn->getResponse());
        $this->assertTrue($status);

        try {
            $conn->getRequest()->setUriPath('/not-exist-path');
            $dp->handshake($conn->getRequest(), $conn->getResponse());
        } catch (Throwable $e) {
            $this->assertSame(WsModuleRouteException::class, get_class($e));
            $this->assertSame('The requested websocket route path "/not-exist-path" is not exist!', $e->getMessage());
        }
    }
}
