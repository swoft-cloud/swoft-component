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

        \server()->log("onClose: conn#{$fd} connection has been closed. client count $total", [], 'debug');

        if (!$meta = $conn->getMetadata()) {
            \server()->log("onClose: conn#{$fd} connection meta info has been lost");
            return;
        }

        \server()->log("onClose: conn#{$fd} meta info:", $meta, 'debug');

        try {
            // Handshake successful callback close handle
            if ($conn->isHandshake()) {
                /** @var WsDispatcher $dispatcher */
                $dispatcher = Container::$instance->getSingleton('wsDispatcher');
                $dispatcher->close($server, $fd);
            }

            // Call on close callback
            \Swoft::trigger(WsServerEvent::AFTER_CLOSE, $fd, $server);
        } catch (\Throwable $e) {
            \server()->log("conn#{$fd} error on handle close, ERR: " . $e->getMessage(), [], 'error');
            \Swoft::trigger(WsServerEvent::ON_ERROR, 'close', $e);
        }

        // Unbind fd
        Session::unbindFd();
        // Remove connection
        Session::destroy($fd);
    }
}
