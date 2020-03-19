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
use Swoft\Context\Context;
use Swoft\Server\Contract\ReceiveInterface;
use Swoft\Session\Session;
use Swoft\SwoftEvent;
use Swoft\Tcp\Server\Context\TcpReceiveContext;
use Swoft\Tcp\Server\Exception\TcpResponseException;
use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\Response;
use Swoft\Tcp\Server\TcpDispatcher;
use Swoft\Tcp\Server\TcpErrorDispatcher;
use Swoft\Tcp\Server\TcpServerBean;
use Swoft\Tcp\Server\TcpServerEvent;
use Swoole\Server;
use Throwable;

/**
 * Class ReceiveListener
 *
 * @since 2.0.4
 * @Bean()
 */
class ReceiveListener implements ReceiveInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     *
     * @throws TcpResponseException
     */
    public function onReceive(Server $server, int $fd, int $reactorId, string $data): void
    {
        $response = Response::new($fd);
        $request  = Request::new($fd, $data, $reactorId);

        server()->log("Receive: conn#{$fd} received client request, begin init context", [], 'debug');

        $sid = (string)$fd;
        $ctx = TcpReceiveContext::new($fd, $request, $response);

        // Storage context
        Context::set($ctx);
        // Bind cid => sid(fd)
        Session::bindCo($sid);

        try {
            // Trigger receive event
            Swoft::trigger(TcpServerEvent::RECEIVE_BEFORE, $fd, $server, $reactorId);

            /** @var TcpDispatcher $dispatcher */
            $dispatcher = Swoft::getSingleton(TcpServerBean::DISPATCHER);

            // Dispatching. allow user disable dispatch.
            if ($dispatcher->isEnable()) {
                $response = $dispatcher->dispatch($request, $response);

                // Trigger package response event
                Swoft::trigger(TcpServerEvent::PACKAGE_RESPONSE, $response);

                $response->send($server);
            }

            // Trigger receive event
            Swoft::trigger(TcpServerEvent::RECEIVE_AFTER, $fd, $server, $reactorId);
        } catch (Throwable $e) {
            server()->log("Receive: conn#{$fd} error: " . $e->getMessage(), [], 'error');
            Swoft::trigger(TcpServerEvent::RECEIVE_ERROR, $e, $fd);

            /** @var TcpErrorDispatcher $errDispatcher */
            $errDispatcher = Swoft::getSingleton(TcpErrorDispatcher::class);
            $errDispatcher->receiveError($e, $response);

            $response->send($server);
        } finally {
            // Defer
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

            // Unbind cid => sid(fd)
            Session::unbindCo();
        }
    }
}
