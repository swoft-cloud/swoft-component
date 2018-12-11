<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Server\Bootstrap\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\SwooleListener;
use Swoft\Bootstrap\Listeners\Interfaces\CloseInterface;
use Swoft\Bootstrap\Listeners\Interfaces\ConnectInterface;
use Swoft\Bootstrap\Listeners\Interfaces\ReceiveInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Console\Helper\ConsoleUtil;
use Swoole\Server;

/**
 *
 * @SwooleListener({
 *     SwooleEvent::ON_RECEIVE,
 *     SwooleEvent::ON_CONNECT,
 *     SwooleEvent::ON_CLOSE
 * },
 * type=SwooleEvent::TYPE_PORT
 * )
 */
class RpcEventListener implements ReceiveInterface, ConnectInterface, CloseInterface
{
    /**
     * RPC 请求每次启动一个协程来处理
     *
     * @param Server $server
     * @param int $fd
     * @param int $fromId
     * @param string $data
     * @throws \InvalidArgumentException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function onReceive(Server $server, int $fd, int $fromId, string $data)
    {
        /** @var \Swoft\Rpc\Server\ServiceDispatcher $dispatcher */
        $dispatcher = App::getBean('ServiceDispatcher');
        $dispatcher->dispatch($server, $fd, $fromId, $data);
    }

    /**
     * 连接成功后回调函数
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $fromId
     *
     */
    public function onConnect(Server $server, int $fd, int $fromId)
    {
        ConsoleUtil::log("A client connects to the server, fd=$fd fromId=$fromId");
    }

    /**
     * 连接断开成功后回调函数
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     */
    public function onClose(Server $server, int $fd, int $reactorId)
    {
        ConsoleUtil::log("A client disconnected from server, fd=$fd reactorId=$reactorId");
    }
}
