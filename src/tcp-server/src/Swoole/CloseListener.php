<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Server\Swoole\CloseInterface;
use Swoft\Session\Session;
use Swoft\SwoftEvent;
use Swoft\Tcp\Server\TcpDispatcher;
use Swoft\Tcp\Server\TcpErrorDispatcher;
use Swoft\Tcp\Server\TcpServerEvent;
use Swoft\Tcp\Server\Connection;
use Swoft\Tcp\Server\Context\TcpCloseContext;
use Swoole\Server;
use Throwable;

/**
 * Class CloseListener
 *
 * @Bean()
 *
 * @since 2.0
 */
class CloseListener implements CloseInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        $sid = (string)$fd;
        $ctx = TcpCloseContext::new($fd, $reactorId);

        // Trigger event
        Swoft::trigger(TcpServerEvent::CLOSE, $server, $fd, $reactorId);


        // Storage context
        Context::set($ctx);
        // Unbind cid => sid(fd)
        Session::bindCo($sid);

        /** @var Connection $conn */
        $conn  = Session::mustGet();
        $total = server()->count() - 1;

        server()->log("Close: conn#{$fd} has been closed. server conn count $total", [], 'debug');
        if (!$meta = $conn->getMetadata()) {
            server()->log("Close: conn#{$fd} connection meta info has been lost");
            return;
        }

        server()->log("Close: conn#{$fd} meta info:", $meta, 'debug');

        try {
            // Handshake successful callback close handle
            if ($conn->isHandshake()) {
                /** @var TcpDispatcher $dispatcher */
                $dispatcher = Swoft::getBean('tcpDispatcher');
                $dispatcher->close($server, $fd);
            }

            // Call on close callback
            Swoft::trigger(TcpServerEvent::AFTER_CLOSE, $fd, $server);
        } catch (Throwable $e) {
            server()->log("Close: conn#{$fd} error on handle close, ERR: " . $e->getMessage(), [], 'error');
            Swoft::trigger(TcpServerEvent::CLOSE_ERROR, $e, $fd);

            /** @var TcpErrorDispatcher $errDispatcher */
            $errDispatcher = BeanFactory::getSingleton(TcpErrorDispatcher::class);
            $errDispatcher->closeError($e, $fd);
        } finally {
            // Defer
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

            // Remove connection
            Swoft::trigger(SwoftEvent::SESSION_COMPLETE, $sid);

            // Unbind cid => sid(fd)
            Session::unbindCo();
        }
    }
}
