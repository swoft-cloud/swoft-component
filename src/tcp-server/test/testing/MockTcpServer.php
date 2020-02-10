<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Testing;

use Swoft\Tcp\Server\Exception\TcpResponseException;
use Swoft\Tcp\Server\Swoole\ConnectListener;
use Swoft\Tcp\Server\Swoole\ReceiveListener;
use Swoft\Tcp\Server\TcpServer;
use Swoole\Server;
use function bean;

/**
 * Class MockTcpServer
 */
class MockTcpServer
{
    /**
     * @var array [fd => 1]
     */
    private $fds = [];

    /**
     * @var int
     */
    private $counter = 1;

    /**
     * @var TcpServer
     */
    private $tcpServer;

    /**
     * TODO ...
     *
     * @var Server
     */
    private $swServer;

    public function start(): void
    {
        $this->tcpServer = new TcpServer();

        TcpServer::setServer($this->tcpServer);
        // $this->swServer  = new Server($ts->getHost(), $ts->getPort(), $ts->getMode(), $ts->getType());
    }

    /**
     * @return Server
     */
    public function getSwooleServer(): Server
    {
        return $this->swServer;
    }

    /**
     */
    public function mockConnect(): void
    {
        $fd = $this->counter++;
        $cl = bean(ConnectListener::class);

        // Save fd
        $this->fds[$fd] = 1;

        $cl->onConnect($this->swServer, $fd, $fd);
    }

    /**
     * @param string $data
     *
     * @throws TcpResponseException
     */
    public function mockReceive(string $data): void
    {
        $fd = $this->counter;
        $rl = bean(ReceiveListener::class);

        $rl->onReceive($this->swServer, $fd, $fd, $data);
    }
}
