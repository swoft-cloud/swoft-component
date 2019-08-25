<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use function get_class;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\MessageParser\RawTextParser;

/**
 * Class ConnectionTest
 */
class ConnectionTest extends WsServerTestCase
{
    public function testBasic(): void
    {
        $fd  = 10;
        $sid = '10';

        $conn = $this->newConnection($fd, '/conn-path');

        $this->assertSame($fd, $conn->getFd());
        $this->assertFalse($conn->isHandshake());

        $conn->setHandshake(true);
        $this->assertTrue($conn->isHandshake());

        $this->assertNull($conn->get('key'));
        $conn->set('key', 'value');
        $this->assertSame('value', $conn->get('key'));

        $this->assertNull($conn->getMetaValue('not-exist'));
        $this->assertSame('127.0.0.1', $conn->getMetaValue('ip'));
        $this->assertSame('1000', $conn->getMetaValue('port'));

        $md = $conn->getMetadata();
        $this->assertArrayHasKey('path', $md);
        $this->assertSame('/conn-path', $md['path']);

        $req = $conn->getRequest();
        $this->assertSame('/conn-path', $req->getUriPath());

        $res = $conn->getResponse();
        $this->assertSame($fd, $res->getCoResponse()->fd);

        $parser = $conn->getParser();
        $this->assertSame(RawTextParser::class, get_class($parser));

        $this->assertTrue(Session::has($sid));
        $conn1 = Session::mustGet();
        $this->assertSame($conn, $conn1);

        Session::destroy($sid);

        $this->assertFalse(Session::has($sid));
    }
}
