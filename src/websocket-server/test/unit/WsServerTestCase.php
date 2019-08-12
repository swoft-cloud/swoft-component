<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\WebSocketServer;
use SwoftTest\WebSocket\Server\Testing\MockHttpRequest;
use SwoftTest\WebSocket\Server\Testing\MockHttpResponse;
use SwoftTest\WebSocket\Server\Testing\MockWsServer;

/**
 * Class WsServerTestCase
 *
 * @since 2.0
 */
abstract class WsServerTestCase extends TestCase
{
    /**
     * @var MockWsServer
     */
    protected $wsServer;

    protected function setUp(): void
    {
        $this->wsServer = new MockWsServer();

        // set server
        WebSocketServer::setServer($this->wsServer);
    }

    protected function tearDown(): void
    {
        // Session::clear();
        WebSocketServer::delServer();
    }

    /**
     * @param int    $fd
     * @param string $path
     *
     * @return Connection
     */
    public function newConnection(int $fd, string $path = '/'): Connection
    {
        $req = MockHttpRequest::new([
            'request_uri' => $path,
        ]);
        $res = MockHttpResponse::new();

        $psrReq = Request::new($req);
        $psrRes = Response::new($res);

        $conn = Connection::new($this->wsServer, $psrReq, $psrRes);

        Session::set((string)$fd, $conn);

        return $conn;
    }
}
