<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use function bean;
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
            $dp->handshake($conn->getRequest(), $conn->getResponse());
        } catch (Throwable $e) {

        }

    }
}
