<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit\ConnTest;

use SwoftTest\WebSocket\Server\Testing\EchoModule;
use Swoole\Coroutine\Http\Client;
use Swoole\WebSocket\Frame;

/**
 * Class EchoModuleTest
 */
class EchoModuleTest extends RealConnTestCase
{
    /**
     * @return Client
     */
    public function testHandshake(): Client
    {
        $client = $this->connectTo('/ws-test/echo');

        $this->assertHandshakeResponse($client);

        /** @var Frame $frame */
        $frame = $client->recv(1.0);
        // \vdump($frame);
        $this->assertSame(1, $frame->opcode);
        $text = $frame->data;
        $this->assertStringContainsString('Opened, welcome to /ws-test/echo!', $text);

        return $client;
    }

    /**
     * @depends testHandshake
     *
     * @param Client $client
     *
     * @return Client
     */
    public function testSendMessage(Client $client): Client
    {
        /** @see EchoModule::reply() */
        $ok = $client->push('testSendMessage');
        $this->assertTrue($ok);

        /** @var Frame $frame */
        $frame = $client->recv(1.0);
        $this->assertSame(1, $frame->opcode);
        $this->assertSame('Recv: testSendMessage', $frame->data);

        return $client;
    }

    /**
     * @depends testSendMessage
     *
     * @param Client $client
     */
    public function testCloseConn(Client $client): void
    {
        $this->assertTrue($client->close());
        $this->assertFalse($client->connected);
    }
}
