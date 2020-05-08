<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Context\Context;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Contract\MessageInterface;
use Swoft\Session\Session;
use Swoft\SwoftEvent;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\Context\WsMessageContext;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Message\Request;
use Swoft\WebSocket\Server\Message\Response;
use Swoft\WebSocket\Server\WsErrorDispatcher;
use Swoft\WebSocket\Server\WsMessageDispatcher;
use Swoft\WebSocket\Server\WsServerEvent;
use Swoole\Websocket\Frame;
use Swoole\Websocket\Server;
use Throwable;
use function server;
use const WEBSOCKET_OPCODE_CLOSE;

/**
 * Class MessageListener
 *
 * @since 2.0
 * @Bean()
 */
class MessageListener implements MessageInterface
{
    /**
     * @Inject("wsMsgDispatcher")
     * @var WsMessageDispatcher
     */
    private $dispatcher;

    /**
     * @param Server $server
     * @param Frame  $frame
     *
     * @throws Throwable
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        $fd  = $frame->fd;

        // Fix: if setting: 'open_websocket_close_frame' => true
        if ($frame->opcode === WEBSOCKET_OPCODE_CLOSE) {
            CLog::info('Close ws#%d connection by close opcode message, reason: %s', $fd, $frame->reason);
            // Swoft::getBean(CloseListener::class)->onClose($server, $fd, 0);
            // NOTICE: swoole will auto call close event.
            return;
        }

        server()->log("Message: conn#{$fd} received message data", [], 'debug');

        $request  = Request::new($frame);
        $response = Response::new($fd);

        /** @var WsMessageContext $ctx */
        $sid = (string)$fd;
        $ctx = WsMessageContext::new($request, $response);

        // Storage context
        Context::set($ctx);
        // Bind cid => sid(fd)
        Session::bindCo($sid);

        try {
            // Trigger message before event.
            // NOTICE: on the event, maybe session has been lost. TODO
            Swoft::trigger(WsServerEvent::MESSAGE_RECEIVE, $fd, $server, $frame);

            /** @var Connection $conn */
            // NOTICE: don't use Session::current(). must use Connection::current(), it can be restore connection from storage.
            $conn = Connection::current();
            $info = $conn->getModuleInfo();

            // Want custom message handle, will don't trigger message parse and dispatch.
            if ($method = $info['eventMethods']['message'] ?? '') {
                $class = $info['class'];
                server()->log("Message: conn#{$fd} call custom message handler '{$class}::{$method}'", [], 'debug');

                /** @var WsModuleInterface $module */
                $module = Swoft::getSingleton($class);
                $module->$method($server, $frame);
            } else {
                // Parse and dispatch message
                $response = $this->dispatcher->dispatch($info, $request, $response);

                // Before call $response->send()
                Swoft::trigger(WsServerEvent::MESSAGE_RESPONSE, $response);

                // Do send response
                if (!$response->isEmpty()) {
                    $response->send();
                }
            }

            // Trigger message after event
            Swoft::trigger(WsServerEvent::MESSAGE_AFTER, $fd, $server, $frame);
        } catch (Throwable $e) {
            CLog::error("Message: conn#{$fd} dispatch error: " . $e->getMessage());
            Swoft::trigger(WsServerEvent::MESSAGE_ERROR, $e, $frame);

            /** @var WsErrorDispatcher $errDispatcher */
            $errDispatcher = BeanFactory::getSingleton(WsErrorDispatcher::class);

            // Do error dispatching
            $response = $errDispatcher->messageError($e, $frame, $response);

            // Do send response
            if (!$response->isEmpty()) {
                $response->send();
            }
        } finally {
            // Defer event
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy event
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

            // Unbind cid => sid(fd)
            Session::unbindCo();
        }
    }
}
