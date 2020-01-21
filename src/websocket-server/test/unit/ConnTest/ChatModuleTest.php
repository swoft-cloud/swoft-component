<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit\ConnTest;

/**
 * Class ChatModuleTest
 */
class ChatModuleTest extends RealConnTestCase
{
    public function testHandshake(): void
    {
        $client = $this->connectTo('/ws-test/chat');

        $this->assertSame(0, $client->errCode);
    }
}
