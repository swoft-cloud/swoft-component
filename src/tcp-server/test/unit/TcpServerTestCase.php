<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Context\Context;
use Swoft\Session\Session;
use Swoft\Tcp\Server\Context\TcpReceiveContext;
use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\Swoole\ReceiveListener;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;
use SwoftTest\Tcp\Server\Testing\MockTcpServer;
use Swoole\Server;

/**
 * Class TcpServerTestCase
 *
 * @since 2.0
 */
abstract class TcpServerTestCase extends TestCase
{
    /**
     * @var MockTcpServer
     */
    protected $tcpServer;

    public function setUp(): void
    {
        parent::setUp();

        $this->tcpServer = new MockTcpServer();
        $this->tcpServer->start();

        // new Server('localhost', 13222);

        // set server
        // MockTcpServer::setServer($this->wsServer);
    }

    /**
     * TODO ...
     *
     * @return Server
     */
    public function swServer(): Server
    {
        return $this->tcpServer->getSwooleServer();
    }

    /**
     * @param int    $fd
     * @param string $data
     * @param int    $rid
     *
     * @return TcpReceiveContext
     * @see ReceiveListener::onReceive()
     */
    public function newTcpReceiveContext(int $fd, string $data, int $rid = 2): TcpReceiveContext
    {
        $sid = (string)$fd;
        $req = Request::new($fd, $data, $rid);
        $res = new MockTcpResponse();

        $ctx = TcpReceiveContext::new($fd, $req, $res);

        // Storage context
        Context::set($ctx);
        // Bind cid => sid(fd)
        Session::bindCo($sid);

        return $ctx;
    }

    /**
     * @see ReceiveListener::onReceive()
     */
    public function delTcpReceiveContext(): void
    {
        // Unbind cid => sid(fd)
        Session::unbindCo();
    }
}
