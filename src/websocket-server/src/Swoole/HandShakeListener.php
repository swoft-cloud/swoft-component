<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Co;
use Swoft\Http\Message\Request as Psr7Request;
use Swoft\Http\Message\Response as Psr7Response;
use Swoft\Server\Swoole\HandShakeInterface;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Helper\WsHelper;
use Swoft\WebSocket\Server\WsDispatcher;
use Swoft\WebSocket\Server\WsServerEvent;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class HandShakeListener
 * @since 2.0
 *
 * @Bean("handShakeListener")
 */
class HandShakeListener implements HandShakeInterface
{
    /**
     * HandShake event
     *
     * @param Request  $request
     * @param Response $response
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onHandShake(Request $request, Response $response): bool
    {
        $fd     = $request->fd;
        $secKey = $request->header['sec-websocket-key'];

        // If sec-websocket-key error
        if (WsHelper::isInvalidSecKey($secKey)) {
            \server()->log("Handshake: shake hands failed with the #$fd. 'sec-websocket-key' is error!");
            return false;
        }

        // Bind fd
        Session::bindFd($fd);

        // Initialize psr7 Request and Response and metadata
        $psr7Req = Psr7Request::new($request);
        $psr7Res = Psr7Response::new($response);

        /** @var Connection $conn Initialize connection */
        $conn = Container::$instance->getPrototype(Connection::class);
        $conn->initialize($fd, $psr7Req, $psr7Res);

        // Bind connection
        Session::set($fd, $conn);
        \Swoft::trigger(WsServerEvent::BEFORE_HANDSHAKE, $fd, $request, $response);

        /** @var WsDispatcher $dispatcher */
        $dispatcher = \bean('wsDispatcher');

        /** @var \Swoft\Http\Message\Response $psr7Res */
        [$status, $psr7Res] = $dispatcher->handshake($psr7Req, $psr7Res);

        $cid  = Co::tid();
        $meta = $conn->getMetadata();

        if (true !== $status) {
            $psr7Res->send();
            \server()->log("Client #$fd handshake check failed, request path {$meta['path']}");
            // NOTICE: Rejecting a handshake still triggers a close event.
            return false;
        }

        // Config response
        $psr7Res = $psr7Res->withStatus(101)->withHeaders(WsHelper::handshakeHeaders($secKey));
        if ($wsProtocol = $request->header['sec-websocket-protocol'] ?? '') {
            $psr7Res = $psr7Res->withHeader('Sec-WebSocket-Protocol', $wsProtocol);
        }

        // Response handshake successfully
        $psr7Res->send();
        $conn->setHandshake(true);

        \server()->log(
            "Handshake: Client #{$fd} handshake successful! path {$meta['path']}, co Id #$cid, Meta:",
            $meta,
            'debug'
        );

        \Swoft::trigger(WsServerEvent::SUCCESS_HANDSHAKE, $fd, $request, $response);

        // Handshaking successful, Manually triggering the open event
        Co::create(function () use ($psr7Req, $fd) {
            $this->onOpen($psr7Req, $fd);
        });

        return true;
    }

    /**
     * @param Psr7Request $request
     * @param int         $fd
     * @throws \Throwable
     */
    public function onOpen(Psr7Request $request, int $fd): void
    {
        // Init fd and coId mapping
        Session::bindFd($fd);
        $server = \server()->getSwooleServer();
        \server()->log("conn#$fd has been opened, co ID #" . Co::tid(), [], 'debug');

        try {
            \Swoft::trigger(WsServerEvent::AFTER_OPEN, $fd, $server, $request);

            /** @var Connection $conn */
            $conn = Session::mustGet();
            $info = $conn->getModule();

            $method = $info['eventMethods']['open'] ?? '';
            if (!$method) {
                $class = $info['class'];

                \server()->log("fd#{$fd} call ws open handler '{$class}->{$method}'", [], 'debug');

                /** @var WsModuleInterface $module */
                $module = Container::$instance->getSingleton($class);
                $module->$method($request);
            }
        } catch (\Throwable $e) {
            $evt = \Swoft::trigger(WsServerEvent::ON_ERROR, 'open', $e, $server);
            if (!$evt->isPropagationStopped()) {
                throw $e;
            }
        } finally {
            // Delete coId from fd mapping
            Session::unbindFd();
        }
    }
}
