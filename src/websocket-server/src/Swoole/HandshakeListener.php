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
use Swoft\Http\Message\Request as Psr7Request;
use Swoft\Http\Message\Response as Psr7Response;
use Swoft\Server\Contract\HandshakeInterface;
use Swoft\Session\Session;
use Swoft\SwoftEvent;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\Context\WsHandshakeContext;
use Swoft\WebSocket\Server\Context\WsOpenContext;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Helper\WsHelper;
use Swoft\WebSocket\Server\WsDispatcher;
use Swoft\WebSocket\Server\WsErrorDispatcher;
use Swoft\WebSocket\Server\WsServerBean;
use Swoft\WebSocket\Server\WsServerEvent;
use Swoole\Coroutine;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Throwable;
use function server;

/**
 * Class HandshakeListener
 *
 * @since 2.0
 * @Bean()
 */
class HandshakeListener implements HandshakeInterface
{
    /**
     * @Inject("wsDispatcher")
     * @var WsDispatcher
     */
    private $wsDispatcher;

    /**
     * Ws Handshake event
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return bool
     * @throws Throwable
     */
    public function onHandshake(Request $request, Response $response): bool
    {
        $fd  = $request->fd;
        $sid = (string)$fd;

        server()->log("Handshake: conn#$fd start an websocket connection request", [], 'debug');

        // If sec-websocket-key error
        $status = false;
        $secKey = $request->header['sec-websocket-key'];
        if (WsHelper::isInvalidSecKey($secKey)) {
            server()->log("Handshake: shake hands failed with the #$fd. 'sec-websocket-key' is error!");
            $response->end('sec-websocket-key is invalid!');
            return false;
        }

        // Initialize psr7 Request and Response
        $psr7Req  = Psr7Request::new($request);
        $psr7Res  = Psr7Response::new($response);
        $wsServer = Swoft::getBean(WsServerBean::SERVER);
        /** @var Swoft\WebSocket\Server\ConnectionManager $manager */
        $manager  = Swoft::getBean(WsServerBean::MANAGER);

        // Initialize connection session and context
        $ctx  = WsHandshakeContext::new($psr7Req, $psr7Res);
        $conn = Connection::new($wsServer, $psr7Req, $psr7Res);

        // Storage context
        Context::set($ctx);

        try {
            // Storage connection and bind cid => sid(fd)
            // old: Session::set($sid, $conn);
            $manager->set($sid, $conn);

            Swoft::trigger(WsServerEvent::HANDSHAKE_BEFORE, $fd, $request, $response);

            /** @var Psr7Response $psr7Res */
            [$status, $psr7Res] = $this->wsDispatcher->handshake($psr7Req, $psr7Res);
            if (true !== $status) {
                $wsServer->log("Handshake: conn#$fd handshake check failed");
                $psr7Res->quickSend();

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
            // NOTICE: must sync connection data to storage
            $manager->set($sid, $conn);
            $psr7Res->quickSend();

            $wsServer->log("Handshake: conn#{$fd} handshake successful! meta:", $meta, 'debug');
            Swoft::trigger(WsServerEvent::HANDSHAKE_SUCCESS, $fd, $request, $response);

            // Handshaking successful, Manually triggering the open event
            // NOTICE:
            //  Cannot use \Swoft\Co::create().
            //  Because this will use the same top-level coroutine ID, if there is a first unbind, it may lead to session loss.
            Coroutine::create(function () use ($psr7Req, $fd) {
                $this->onOpen($psr7Req, $fd);
            });
        } catch (Throwable $e) {
            Swoft::trigger(WsServerEvent::HANDSHAKE_ERROR, $e, $request);

            /** @var WsErrorDispatcher $errDispatcher */
            $errDispatcher = BeanFactory::getSingleton(WsErrorDispatcher::class);

            $psr7Res = $errDispatcher->handshakeError($e, $psr7Res);
            $psr7Res->quickSend();

            // Should clear session data on handshake fail
            $manager->destroy($sid);
        } finally {
            // Defer
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

            // Unbind cid => sid(fd)
            Session::unbindCo();
        }

        return $status;
    }

    /**
     * @param Psr7Request $request
     * @param int         $fd
     *
     * @throws Throwable
     */
    public function onOpen(Psr7Request $request, int $fd): void
    {
        $ctx = WsOpenContext::new($request);
        // Storage context
        Context::set($ctx);
        // Bind cid => sid(fd)
        Session::bindCo((string)$fd);

        $server = server()->getSwooleServer();
        server()->log("Open: conn#{$fd} has been opened", [], 'debug');

        try {
            Swoft::trigger(WsServerEvent::OPEN_BEFORE, $fd, $server, $request);

            /** @var Connection $conn */
            $conn = Session::mustGet();
            $info = $conn->getModuleInfo();

            if ($method = $info['eventMethods']['open'] ?? '') {
                $class = $info['class'];

                server()->log("Open: conn#{$fd} call ws open handler '{$class}::{$method}'", [], 'debug');

                /** @var WsModuleInterface $module */
                $module = BeanFactory::getSingleton($class);
                $module->$method($request, $fd);
            }

            Swoft::trigger(WsServerEvent::OPEN_AFTER, $fd, $server, $request);
        } catch (Throwable $e) {
            Swoft::trigger(WsServerEvent::OPEN_ERROR, $e, $request);

            /** @var WsErrorDispatcher $errDispatcher */
            $errDispatcher = BeanFactory::getSingleton(WsErrorDispatcher::class);
            $errDispatcher->openError($e, $request);
        } finally {
            // Defer
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Destroy Coroutine
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);

            // Unbind cid => sid(fd)
            Session::unbindCo();
        }
    }
}
