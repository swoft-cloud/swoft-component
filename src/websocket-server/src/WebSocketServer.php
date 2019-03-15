<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Server;
use Swoft\Server\Swoole\SwooleEvent;
use Swoole\Websocket\Frame;

/**
 * Class WebSocketServer
 *
 * @Bean("wsServer")
 *
 * @since 2.0
 */
class WebSocketServer extends Server
{
    protected static $serverType = 'WebSocket';

    /**
     * Start swoole server
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new \Co\Websocket\Server($this->host, $this->port, $this->mode, $this->type);

        $this->startSwoole();
    }

    /*****************************************************************************
     * handle WS events
     ****************************************************************************/


    /*****************************************************************************
     * helper methods for send message
     ****************************************************************************/

    /**
     * Send data to client by frame object.
     * NOTICE: require swoole version >= 4.2.0
     *
     * @param Frame $frame
     * @return bool
     */
    public function pushFrame(Frame $frame): bool
    {
        return $this->swooleServer->push($frame);
    }

    /**
     * @param int    $fd
     * @param string $data Data for send to client. NOTICE: max size is 2M.
     * @param int    $opcode
     * text:   WEBSOCKET_OPCODE_TEXT   = 1
     * binary: WEBSOCKET_OPCODE_BINARY = 2
     * close:  WEBSOCKET_OPCODE_CLOSE  = 8
     * ping:   WEBSOCKET_OPCODE_PING   = 9
     * pong:   WEBSOCKET_OPCODE_PONG   = 10
     * @param bool   $finish
     * @return bool
     */
    public function push(int $fd, string $data, int $opcode = \WEBSOCKET_OPCODE_TEXT, bool $finish = true): bool
    {
        // if (!$this->swooleServer->exist($fd)) {
        if (!$this->swooleServer->isEstablished($fd)) {
            return false;
        }

        return $this->swooleServer->push($fd, $data, $opcode, $finish);
    }

    /**
     * send message to client(s)
     * @param string    $data
     * @param int|array $receivers
     * @param int|array $excluded
     * @param int       $sender
     * @param int       $pageSize
     * @return int
     */
    public function send(string $data, $receivers = 0, $excluded = 0, int $sender = 0, int $pageSize = 50): int
    {
        if (!$data) {
            return 0;
        }

        $receivers = (array)$receivers;
        $excluded  = (array)$excluded;

        // only one receiver
        if (1 === \count($receivers)) {
            return $this->sendTo((int)\array_shift($receivers), $data, $sender);
        }

        // to all
        if (!$excluded && !$receivers) {
            return $this->sendToAll($data, $sender, $pageSize);
        }

        // to some
        return $this->sendToSome($data, $receivers, $excluded, $sender, $pageSize);
    }

    /**
     * Send a message to the specified user 发送消息给指定的用户
     * @param int    $receiver 接收者 fd
     * @param string $data
     * @param int    $sender 发送者 fd
     * @param int    $opcode
     * @return int
     */
    public function sendTo(int $receiver, string $data, int $sender = 0, int $opcode = \WEBSOCKET_OPCODE_TEXT): int
    {
        $finish   = true;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;

        $this->log("(private)The #{$fromUser} send message to the user #{$receiver}. Data: {$data}");

        return $this->swooleServer->push($receiver, $data, $opcode, $finish) ? 1 : 0;
    }

    /**
     * Broadcast message to all user, but will exclude sender
     * @param string $data Message data
     * @param int    $sender Sender FD
     * @param int[]  $receivers Designated recipients(FD list)
     * @param int[]  $excluded Recipient to be excluded
     * @return int Return send count
     */
    public function broadcast(string $data, array $receivers = [], array $excluded = [], int $sender = 0): int
    {
        if (!$data) {
            return 0;
        }

        // only one receiver
        if (1 === \count($receivers)) {
            return $this->sendTo((int)\array_shift($receivers), $data, $sender);
        }

        // excepted itself
        if ($sender) {
            $excluded[] = $sender;
        }

        // to all
        if (!$excluded && !$receivers) {
            return $this->sendToAll($data, $sender);
        }

        // to some
        return $this->sendToSome($data, $receivers, $excluded, $sender);
    }

    /**
     * Send message to all connections
     * @param string $data
     * @param int    $sender
     * @param int    $pageSize
     * @return int
     */
    public function sendToAll(string $data, int $sender = 0, int $pageSize = 50): int
    {
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;
        $this->log("(broadcast)The #{$fromUser} send a message to all users. Data: {$data}");

        return $this->pageEach(function (int $fd) use ($data) {
            $this->swooleServer->push($fd, $data);
        }, $pageSize);
    }

    /**
     * @param string $data
     * @param array  $receivers
     * @param array  $excluded
     * @param int    $sender
     * @param int    $pageSize
     * @return int
     */
    public function sendToSome(
        string $data,
        array $receivers = [],
        array $excluded = [],
        int $sender = 0,
        int $pageSize = 50
    ): int {
        $count    = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;

        // to receivers
        if ($receivers) {
            $this->log("(broadcast)The #{$fromUser} gave some specified user sending a message. Data: {$data}");

            foreach ($receivers as $fd) {
                if ($this->swooleServer->isEstablished($fd)) {
                    $count++;
                    $this->swooleServer->push($fd, $data);
                }
            }

            return $count;
        }

        // to special users
        $excluded = $excluded ? (array)\array_flip($excluded) : [];

        $this->log("(broadcast)The #{$fromUser} send the message to everyone except some people. Data: {$data}");

        return $this->pageEach(function (int $fd) use ($excluded, $data) {
            if (isset($excluded[$fd])) {
                return;
            }

            $this->swooleServer->push($fd, $data);
        }, $pageSize);
    }

    /*****************************************************************************
     * helper methods
     ****************************************************************************/

    /**
     * Traverse all valid WS connections FD
     * @param callable $handler
     * @return int
     */
    public function each(callable $handler): int
    {
        $count = 0;

        foreach ($this->swooleServer->connections as $fd) {
            if ($this->swooleServer->isEstablished($fd)) {
                $count++;
                $handler($fd);
            }
        }

        return $count;
    }

    /**
     * Pagination traverse all valid WS connection
     *
     * @param callable $handler
     * @param int      $pageSize
     * @return int
     */
    public function pageEach(callable $handler, int $pageSize = 50): int
    {
        $count = $startFd = 0;

        while (true) {
            $fdList = (array)$this->swooleServer->getClientList($startFd, $pageSize);

            // is empty
            if (($num = \count($fdList)) === 0) {
                break;
            }

            $count += $num;

            /** @var $fdList array */
            foreach ($fdList as $fd) {

                if ($this->swooleServer->isEstablished($fd)) {
                    $handler($fd);
                }
            }

            // is last page.
            if ($num < $pageSize) {
                break;
            }

            // get start fd for next page.
            $startFd = \end($fdList);
        }

        return $count;
    }

    /**
     * Disconnect for client
     * @param int    $fd
     * @param int    $code
     * @param string $reason
     * @return bool
     */
    public function disconnect(int $fd, int $code = 0, string $reason = ''): bool
    {
        return $this->swooleServer->disconnect($fd, $code, $reason);
    }

    /**
     * response data to client by socket connection
     * @param int    $fd
     * @param string $data
     * param int $length
     * @return bool
     */
    public function writeTo(int $fd, string $data): bool
    {
        return $this->swooleServer->send($fd, $data);
    }

    /**
     * @param int $fd
     * @return bool
     */
    public function exist(int $fd): bool
    {
        return $this->swooleServer->exist($fd);
    }

    /**
     * Check it is valid websocket connection(has been handshake)
     * @param int $fd
     * @return bool
     */
    public function isEstablished(int $fd): bool
    {
        return $this->swooleServer->isEstablished($fd);
    }

    /**
     * @return bool
     */
    public function httpIsEnabled(): bool
    {
        return isset($this->on[SwooleEvent::REQUEST]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->swooleServer->connections);
    }
}
