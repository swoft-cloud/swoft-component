<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit\ConnTest;

use Swoft\WebSocket\Server\MessageParser\JsonParser;
use SwoftTest\WebSocket\Server\Testing\Chat\ChatController;
use SwoftTest\WebSocket\Server\Testing\Chat\UserController;
use SwoftTest\WebSocket\Server\Testing\ChatModule;
use Swoole\Coroutine\Http\Client;
use Swoole\WebSocket\Frame;

/**
 * Class ChatModuleTest
 */
class ChatModuleTest extends RealConnTestCase
{
    /**
     * @return Client
     */
    public function testHandshake(): Client
    {
        $client = $this->connectTo('/ws-test/chat');

        $this->assertHandshakeResponse($client);

        // special
        $name = 'ws-conn';
        $this->assertArrayHasKey($name, $headers = $client->headers);
        $this->assertSame('in testing', $headers[$name]);

        /** @var Frame $frame */
        $frame = $client->recv(1.0);
        // \vdump($frame);
        $this->assertSame(1, $frame->opcode);
        $this->assertSame(ChatModule::class . '::onOpen', $frame->data);

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
        /** @see UserController::reply() */
        $ok = $client->push('{"cmd": "user.reply", "data": "testSendMessage"}');
        $this->assertTrue($ok);

        /** @var Frame $frame */
        $frame = $client->recv(1.0);
        $this->assertTrue($frame->finish);
        $this->assertSame(1, $frame->opcode);

        // check reply data
        $ret = $frame->data;
        $this->assertSame('{"cmd":"user.reply","data":"RECV: testSendMessage","ext":[]}', $ret);

        $parser  = new JsonParser;
        $message = $parser->decode($ret);
        $this->assertSame([], $message->getExt());
        $this->assertSame('user.reply', $message->getCmd());
        $this->assertSame('RECV: testSendMessage', $message->getData());

        // ------- test use middleware -------

        /** @see ChatController::send() */
        $ok = $client->push('{"cmd": "chat.send", "data": "testSendMessage1"}');
        $this->assertTrue($ok);

        /** @var Frame $frame */
        $frame = $client->recv(1.0);
        $this->assertTrue($frame->finish);
        $this->assertSame(1, $frame->opcode);

        // check reply data
        $parser  = new JsonParser;
        $message = $parser->decode($frame->data);

        $this->assertSame('chat.send', $message->getCmd());
        $body = '>USER1 >USER2 SwoftTest\\WebSocket\\Server\\Testing\\Chat\\ChatController::send USER2> USER1>';
        $this->assertSame($body, $message->getData());

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
