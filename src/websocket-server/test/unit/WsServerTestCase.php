<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Connection;
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

    public function setUp(): void
    {
        $this->wsServer = new MockWsServer();
    }

    public function addSession(int $fd): void
    {
        $req = MockHttpRequest::new();
        $res = MockHttpResponse::new();

        $psrReq = Request::new($req);
        $psrRes = Response::new($res);

        $conn = Connection::new($fd, $psrReq, $psrRes);

        Session::set((string)$fd, $conn);
    }
}
