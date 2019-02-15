<?php

namespace Swoft\WebSocket\Server\Swoole;


use Co\Websocket\Frame;
use Co\Websocket\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Connection\Connections;
use Swoft\Context\Context;
use Swoft\Server\Swoole\MessageInterface;
use Swoft\WebSocket\Server\Exception\WsServerException;
use Swoft\WebSocket\Server\Router\Router;
use Swoft\WebSocket\Server\WsContext;
use Swoft\WebSocket\Server\WsEvent;

/**
 * Class MessageListener
 *
 * @Bean("messageListener")
 *
 * @since 2.0
 */
class MessageListener implements MessageInterface
{
    /**
     * @param Server $server
     * @param Frame  $frame
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        $fd = $frame->fd;

        /** @var WsContext $ctx */
        $ctx = \bean(WsContext::class);
        $ctx->initialize($frame);

        // storage context
        Context::set($ctx);
        // init fd and coId mapping
        Connections::bindFd($fd);

        \Swoft::trigger(WsEvent::ON_MESSAGE, null, $server, $frame);

        \server()->log("received message: {$frame->data} from fd #{$fd}, co ID #" . Co::tid(), [], 'debug');

        /** @see Dispatcher::message() */
        \bean('wsDispatcher')->message($server, $frame);

        try {
            $conn = Connections::mustGet();
            // get request path
            // $path = $conn->getMetaValue('path');
            $path = $conn->getRequest()->getUri()->getPath();

            /** @var Router $router */
            $router = \Swoft::getBean('wsRouter');

            if (!$module = $router->match($path)) {
                // Should never happen
                throw new WsServerException('module info has been lost of the ' . $path);
            }

            $dataParser = $module['messageParser'];
        } catch (\Throwable $e) {

        }

        // destroy context
        Context::destroy();
        // delete coId from fd mapping
        Connections::unbindFd();
    }
}
