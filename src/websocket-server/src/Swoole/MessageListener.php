<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Context\Context;
use Swoft\Server\Swoole\MessageInterface;
use Swoft\Session\Session;
use Swoft\SwoftEvent;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\WsMessageContext;
use Swoft\WebSocket\Server\WsDispatcher;
use Swoft\WebSocket\Server\WsServerEvent;
use Swoole\Websocket\Frame;
use Swoole\Websocket\Server;

/**
 * Class MessageListener
 *
 * @Bean()
 *
 * @since 2.0
 */
class MessageListener implements MessageInterface
{
    /**
     * @param Server $server
     * @param Frame  $frame
     * @throws \Throwable
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        /** @var WsMessageContext $ctx */
        $fd  = $frame->fd;
        $sid = (string)$fd;
        $ctx = BeanFactory::getPrototype(WsMessageContext::class);
        $ctx->initialize($frame);

        // Storage context
        Context::set($ctx);
        // Init fd and coId mapping
        Session::bindCo($sid);

        /** @var WsDispatcher $dispatcher */
        $dispatcher = BeanFactory::getSingleton('wsDispatcher');

        try {
            \server()->log("Message: conn#{$fd} received message: {$frame->data}", [], 'debug');
            \Swoft::trigger(WsServerEvent::BEFORE_MESSAGE, $fd, $server, $frame);

            // Parse and dispatch message
            $dispatcher->message($server, $frame);

            \Swoft::trigger(WsServerEvent::AFTER_MESSAGE, $fd, $server, $frame);
        } catch (\Throwable $e) {
            \server()->log("Message: conn#{$fd} error: " . $e->getMessage(), [], 'error');

            if ($e instanceof WsMessageRouteException) {
                \server()->push($fd, $e->getMessage());
            } else {
                // TODO: Close connection on error ?
                $dispatcher->error($e, 'message');
            }
        } finally {
            // Defer
            \Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy
            \Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

            // Delete coId from fd mapping
            Session::unbindCo();
        }
    }
}
