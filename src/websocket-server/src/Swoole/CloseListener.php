<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 13:37
 */

namespace Swoft\WebSocket\Server\Swoole;


use Co\Server as CoServer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Connection\Connections;
use Swoft\Server\Swoole\CloseInterface;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\WsEvent;

/**
 * Class CloseListener
 * @since 2.0
 * @package Swoft\WebSocket\Server\Swoole
 *
 * @Bean("closeListener")
 */
class CloseListener implements CloseInterface
{
    /**
     * Close event
     *
     * @param CoServer $server
     * @param int      $fd
     * @param int      $reactorId
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onClose(CoServer $server, int $fd, int $reactorId): void
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
        $conn = Connections::get();

        if (!$meta = $conn->getMetadata()) {
            \server()->log("onClose: Client #{$fd} connection meta info has been lost");
            return;
        }

        \server()->log("onClose: Client #{$fd} meta info:", $meta, 'debug');

        // 握手成功的才回调 close
        if ($meta['handshake']) {
            /** @see Dispatcher::close() */
            \bean('wsDispatcher')->close($server, $fd);
        }

        // call on close callback
        \Swoft::trigger(WsEvent::ON_CLOSE, $fd, $server);

        // unbind fd
        Connections::unbindFd();
        // remove connection
        Connections::destroy($fd);
    }
}
