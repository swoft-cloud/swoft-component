<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\WebSocketServer;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;
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
    use CommonTestAssertTrait;

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
        WebSocketServer::delServer();
    }

    public static function tearDownAfterClass(): void
    {
        Session::clear();
    }

    /**
     * @param int    $fd
     * @param string $path
     *
     * @return Request
     */
    protected function mockHttpRequest(int $fd = 1, string $path = '/'): Request
    {
        $req = MockHttpRequest::new([
            'request_uri' => $path,
        ]);

        $req->fd = $fd;

        return Request::new($req);
    }

    /**
     * @param int $fd
     *
     * @return Response
     */
    protected function mockHttpResponse(int $fd): Response
    {
        $res = MockHttpResponse::new();

        $res->fd = $fd;
        return Response::new($res);
    }

    /**
     * @param int    $fd
     * @param string $path
     *
     * @return Connection
     */
    protected function newConnection(int $fd, string $path = '/'): Connection
    {
        $psrReq = $this->mockHttpRequest($fd, $path);
        $psrRes = $this->mockHttpResponse($fd);

        $conn = Connection::new($this->wsServer, $psrReq, $psrRes);

        Session::set((string)$fd, $conn);

        return $conn;
    }

    protected function rmConnection(string $sid): void
    {
        Session::destroy($sid);
    }
}
