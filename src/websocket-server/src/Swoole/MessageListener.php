<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Context\Context;
use Swoft\Server\Swoole\MessageInterface;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\WsContext;
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
        /** @var WsContext $ctx */
        $fd  = $frame->fd;
        $ctx = BeanFactory::getPrototype(WsContext::class);
        $ctx->initialize($frame);

        // Storage context
        Context::set($ctx);
        // Init fd and coId mapping
        Session::bindFd($fd);

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
                // TODO: Close connection if event handle is not stopped
                $dispatcher->error($e, 'message');
            }
        } finally {
            // Destroy context
            Context::destroy();
            // Delete coId from fd mapping
            Session::unbindFd();
        }
    }
}
