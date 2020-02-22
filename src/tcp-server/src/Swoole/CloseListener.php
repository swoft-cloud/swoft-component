<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Context\Context;
use Swoft\Server\Contract\CloseInterface;
use Swoft\Session\Session;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\SwoftEvent;
use Swoft\Tcp\Server\Connection;
use Swoft\Tcp\Server\Context\TcpCloseContext;
use Swoft\Tcp\Server\TcpErrorDispatcher;
use Swoft\Tcp\Server\TcpServerBean;
use Swoft\Tcp\Server\TcpServerEvent;
use Swoole\Server;
use Throwable;

/**
 * Class CloseListener
 *
 * @since 2.0.4
 * @Bean()
 */
class CloseListener implements CloseInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        $sid = (string)$fd;
        $ctx = TcpCloseContext::new($fd, $reactorId);

        // Storage context
        Context::set($ctx);
        // Bind cid => sid(fd)
        Session::bindCo($sid);

        /** @var Swoft\Tcp\Server\ConnectionManager $manager */
        $manager = Swoft::getBean(TcpServerBean::MANAGER);

        try {
            // Trigger event
            Swoft::trigger(TcpServerEvent::CLOSE, $fd, $server, $reactorId);

            // Manually close non-current worker connections
            // - Session not exist the worker, notify other worker clear session.
            if (!Session::has($sid)) {
                $this->notifyOtherWorkersClose($fd, $sid, $server->worker_id);
            } else {
                $total = server()->count() - 1;

                server()->log("Close: conn#{$fd} has been closed. server conn count $total", [], 'debug');

                /** @var Connection $conn */
                // $conn = Session::mustGet();
                $conn = $manager->current();
                if (!$meta = $conn->getMetadata()) {
                    server()->log("Close: conn#{$fd} connection meta info has been lost");
                    return;
                }

                server()->log("Close: conn#{$fd} meta info:", $meta, 'debug');
            }
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
            $manager->destroy($sid);

            // Unbind cid => sid(fd)
            Session::unbindCo();
        }
    }

    /**
     * @param int    $fd
     * @param string $sid
     * @param int    $workerId
     */
    private function notifyOtherWorkersClose(int $fd, string $sid, int $workerId): void
    {
        $data = [
            'from'  => 'tcpServer',
            'event' => 'onClose',
            'fd'    => $fd,
            'sid'   => $sid,
        ];

        // $server->sendMessage($message, $dst_worker_id);
        server()->log("Close: conn#{$fd} session not exist current worker, notify other worker handle");
        server()->notifyWorkers(JsonHelper::encode($data), [], [$workerId]);
    }
}
