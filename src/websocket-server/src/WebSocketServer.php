<?php

namespace Swoft\WebSocket\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Server;

/**
 * Class WebSocketServer
 *
 * @Bean("wsServer")
 *
 * @since 2.0
 */
class WebSocketServer extends Server
{
    /**
     * Start
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new \Co\Websocket\Server($this->host, $this->port);

        $this->startSwoole();
    }

    /*****************************************************************************
     * handle WS events
     ****************************************************************************/


    /*****************************************************************************
     * helper methods for send message
     ****************************************************************************/

    /**
     * @param string $fd
     * @param string $data
     * @param bool $isBinary
     * @param bool $finish
     * @return bool
     */
    public function push(string $fd, string $data, $isBinary = false, bool $finish = true): bool
    {
        if (!$this->swooleServer->exist($fd)) {
            return false;
        }

        return $this->swooleServer->push($fd, $data, $isBinary, $finish);
    }

    /**
     * send message to client(s)
     * @param string $data
     * @param int|array $receivers
     * @param int|array $excluded
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function send(string $data, $receivers = 0, $excluded = 0, int $sender = 0, int $pageSize = 50): int
    {
        if (!$data) {
            return 0;
        }

        $receivers = (array)$receivers;
        $excluded = (array)$excluded;

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
     * @param int $receiver 接收者 fd
     * @param string $data
     * @param int $sender 发送者 fd
     * @return int
     */
    public function sendTo(int $receiver, string $data, int $sender = 0): int
    {
        $finish = true;
        $opcode = 1;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;

        $this->log("(private)The #{$fromUser} send message to the user #{$receiver}. Data: {$data}");

        return $this->swooleServer->push($receiver, $data, $opcode, $finish) ? 1 : 0;
    }

    /**
     * broadcast message, will exclude sender
     * @param string $data 消息数据
     * @param int $sender 发送者
     * @param int[] $receivers 指定接收者们
     * @param int[] $excluded 要排除的接收者
     * @return int Return socket last error number code.  gt 0 on failure, eq 0 on success
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
     * send message to all connections
     * @param string $data
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function sendToAll(string $data, int $sender = 0, int $pageSize = 50): int
    {
        $startFd = 0;
        $count = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;
        $this->log("(broadcast)The #{$fromUser} send a message to all users. Data: {$data}");

        while (true) {
            $fdList = $this->swooleServer->connection_list($startFd, $pageSize);

            if ($fdList === false || ($num = \count($fdList)) === 0) {
                break;
            }

            $count += $num;
            $startFd = \end($fdList);

            /** @var $fdList array */
            foreach ($fdList as $fd) {
                $info = $this->getClientInfo($fd);

                if ($info && $info['websocket_status'] > 0) {
                    $this->swooleServer->push($fd, $data);
                }
            }
        }

        return $count;
    }

    /**
     * @param string $data
     * @param array $receivers
     * @param array $excluded
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function sendToSome(string $data, array $receivers = [], array $excluded = [], int $sender = 0, int $pageSize = 50): int
    {
        $count = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;

        // to receivers
        if ($receivers) {
            $this->log("(broadcast)The #{$fromUser} gave some specified user sending a message. Data: {$data}");

            foreach ($receivers as $receiver) {
                if ($this->exist($receiver)) {
                    $count++;
                    $this->swooleServer->push($receiver, $data);
                }
            }

            return $count;
        }

        // to special users
        $startFd = 0;
        $excluded = $excluded ? (array)\array_flip($excluded) : [];

        $this->log("(broadcast)The #{$fromUser} send the message to everyone except some people. Data: {$data}");

        while (true) {
            $fdList = $this->swooleServer->connection_list($startFd, $pageSize);

            if ($fdList === false || ($num = \count($fdList)) === 0) {
                break;
            }

            $count += $num;
            $startFd = \end($fdList);

            /** @var $fdList array */
            foreach ($fdList as $fd) {
                if (isset($excluded[$fd]) || !$this->exist($fd)) {
                    continue;
                }

                $this->swooleServer->push($fd, $data);
            }
        }

        return $count;
    }

    /*****************************************************************************
     * helper methods
     ****************************************************************************/

    /**
     * response data to client by socket connection
     * @param int $fd
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
     * @return int
     */
    public function count(): int
    {
        return \count($this->swooleServer->connections);
    }

    /**
     * @return int
     */
    public function getErrorNo(): int
    {
        return $this->swooleServer->getLastError();
    }

    /**
     * @param int $fd
     * @return array
     */
    public function getClientInfo(int $fd): array
    {
        return $this->swooleServer->getClientInfo($fd);
    }
}
