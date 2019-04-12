<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
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
 * @Bean()
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
     * @throws \Throwable
     */
    public function onHandShake(Request $request, Response $response): bool
    {
        $fd  = $request->fd;
        $sid = (string)$fd;

        \server()->log("Handshake: conn#$fd start an websocket connection request", [], 'debug');

        // If sec-websocket-key error
        $secKey = $request->header['sec-websocket-key'];
        if (WsHelper::isInvalidSecKey($secKey)) {
            \server()->log("Handshake: shake hands failed with the #$fd. 'sec-websocket-key' is error!");
            return false;
        }

        // Bind fd
        Session::bindCo($sid);

        // Initialize psr7 Request and Response and metadata
        $psr7Req = Psr7Request::new($request);
        $psr7Res = Psr7Response::new($response);

        /** @var Connection $conn Initialize connection */
        $conn = BeanFactory::getPrototype(Connection::class);
        $conn->initialize($fd, $psr7Req, $psr7Res);

        // Bind connection
        Session::set($sid, $conn);
        \Swoft::trigger(WsServerEvent::BEFORE_HANDSHAKE, $fd, $request, $response);

        /** @var WsDispatcher $dispatcher */
        $dispatcher = BeanFactory::getSingleton('wsDispatcher');

        /** @var \Swoft\Http\Message\Response $psr7Res */
        [$status, $psr7Res] = $dispatcher->handshake($psr7Req, $psr7Res);
        if (true !== $status) {
            $psr7Res->send();
            \server()->log("Handshake: conn#$fd handshake check failed");
            // NOTICE: Rejecting a handshake still triggers a close event.
            return false;
        }

        // Config response
        $psr7Res = $psr7Res->withStatus(101)->withHeaders(WsHelper::handshakeHeaders($secKey));
        if ($wsProtocol = $request->header['sec-websocket-protocol'] ?? '') {
            $psr7Res = $psr7Res->withHeader('Sec-WebSocket-Protocol', $wsProtocol);
        }

        // Response handshake successfully
        $meta = $conn->getMetadata();
        $conn->setHandshake(true);
        $psr7Res->quickSend();

        \server()->log("Handshake: conn#{$fd} handshake successful! meta:", $meta, 'debug');
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
        Session::bindCo((string)$fd);

        $server = \server()->getSwooleServer();
        \server()->log("Open: conn#$fd has been opened", [], 'debug');

        try {
            \Swoft::trigger(WsServerEvent::AFTER_OPEN, $fd, $server, $request);

            /** @var Connection $conn */
            $conn = Session::mustGet();
            $info = $conn->getModuleInfo();

            if ($method = $info['eventMethods']['open'] ?? '') {
                $class = $info['class'];

                \server()->log("Open: conn#{$fd} call ws open handler '{$class}::{$method}'", [], 'debug');

                /** @var WsModuleInterface $module */
                $module = BeanFactory::getSingleton($class);
                $module->$method($request, $fd);
            }
        } catch (\Throwable $e) {
            $evt = \Swoft::trigger(WsServerEvent::ON_ERROR, 'open', $e, $server);

            if (!$evt->isPropagationStopped()) {
                throw $e;
            }
        } finally {
            // Delete coId from fd mapping
            Session::unbindCo();
        }
    }
}
