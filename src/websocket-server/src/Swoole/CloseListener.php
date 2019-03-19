<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Server\Swoole\CloseInterface;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\WsDispatcher;
use Swoft\WebSocket\Server\WsServerEvent;
use Swoole\Server;

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
     * @param Server|\Swoole\WebSocket\Server $server
     * @param int                             $fd
     * @param int                             $reactorId
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        // Only allow handshake success conn
        if (!$server->isEstablished($fd)) {
            Session::unbindFd();
            Session::destroy($fd);
            return;
        }

        /** @var Connection $conn */
        $conn  = Session::get();
        $total = \server()->count();

        \server()->log("onClose: Client #{$fd} connection has been closed. client count $total", [], 'debug');

        if (!$meta = $conn->getMetadata()) {
            \server()->log("onClose: Client #{$fd} connection meta info has been lost");
            return;
        }

        \server()->log("onClose: Client #{$fd} meta info:", $meta, 'debug');

        /** @var WsDispatcher $dispatcher */
        $dispatcher = Container::$instance->getSingleton('wsDispatcher');
        // 握手成功的才回调 close
        if ($conn->isHandshake()) {
            /** @see WsDispatcher::close() */
            \bean('wsDispatcher')->close($server, $fd);
        }

        // Call on close callback
        \Swoft::trigger(WsServerEvent::AFTER_CLOSE, $fd, $server);

        // Unbind fd
        Session::unbindFd();
        // Remove connection
        Session::destroy($fd);
    }
}
