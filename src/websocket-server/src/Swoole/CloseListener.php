<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoole\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Session\Session;
use Swoft\Server\Swoole\CloseInterface;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\WsServerEvent;

/**
 * Class CloseListener
 * @since 2.0
 *
 * @Bean("closeListener")
 */
class CloseListener implements CloseInterface
{
    /**
     * Close event
     *
     * @param Server $server
     * @param int      $fd
     * @param int      $reactorId
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        /*
        WEBSOCKET_STATUS_CONNECTION = 1，连接进入等待握手
        WEBSOCKET_STATUS_HANDSHAKE = 2，正在握手
        WEBSOCKET_STATUS_FRAME = 3，已握手成功等待浏览器发送数据帧
        */
        $fdInfo = $server->getClientInfo($fd);

        if ($fdInfo['websocket_status'] < 1) {
            return;
        }

        $total = \server()->count();
        \server()->log("onClose: Client #{$fd} connection has been closed. client count $total, client info:", $fdInfo,
            'debug');

        /** @var Connection $conn */
        $conn = Session::get();

        if (!$meta = $conn->getMetadata()) {
            \server()->log("onClose: Client #{$fd} connection meta info has been lost");
            return;
        }

        \server()->log("onClose: Client #{$fd} meta info:", $meta, 'debug');

        // 握手成功的才回调 close
        if ($conn->isHandshake()) {
            /** @see Dispatcher::close() */
            \bean('wsDispatcher')->close($server, $fd);
        }

        // call on close callback
        \Swoft::trigger(WsServerEvent::AFTER_CLOSE, $fd, $server);

        // unbind fd
        Session::unbindFd();
        // remove connection
        Session::destroy($fd);
    }
}
