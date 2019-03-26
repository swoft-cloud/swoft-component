<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Exception\WsHandShakeException;
use Swoft\WebSocket\Server\Exception\WsMessageException;
use Swoft\WebSocket\Server\Exception\WsMessageParseException;
use Swoft\WebSocket\Server\Exception\WsRouteException;
use Swoft\WebSocket\Server\Exception\WsServerException;
use Swoft\WebSocket\Server\MessageParser\TextParser;
use Swoft\WebSocket\Server\Router\Router;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class WsDispatcher
 *
 * @since 1.0
 * @Bean("wsDispatcher")
 */
class WsDispatcher
{
    /**
     * Dispatch handshake request
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return array eg. [status, response]
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     * @throws \InvalidArgumentException
     */
    public function handshake(Request $request, Response $response): array
    {
        /** @var Router $router */
        $router = Container::$instance->getSingleton('wsRouter');

        try {
            /** @var Connection $conn */
            $conn = Session::mustGet();
            $path = $request->getUriPath();

            if (!$info = $router->match($path)) {
                throw new WsRouteException(\sprintf(
                    'The requested websocket route path "%s" is not exist!',
                    $path
                ));
            }

            $class = $info['class'];
            $conn->setModule($info);

            \server()->log("found handler for path '$path', ws module class is $class", [], 'debug');

            /** @var WsModuleInterface $module */
            $module = Container::$instance->getSingleton($class);
            $method = $info['eventMethods']['handShake'] ?? '';

            // Auto handShake
            if (!$method) {
                return [true, $response->withAddedHeader('swoft-ws-handshake', 'auto')];
            }

            return $module->$method($request, $response);
        } catch (\Throwable $e) {
            if ($e instanceof WsRouteException) {
                /** @var Response|static $response */
                $response = $response
                    ->withStatus(404)
                    ->withAddedHeader('Failure-Reason', 'Route not found');
            } else { // Other error
                $e = new WsHandShakeException('handshake error: ' . $e->getMessage(), -500, $e);
            }

            \Swoft::trigger(WsServerEvent::ON_ERROR, 'handshake', $e);
        }

        return [false, $response];
    }

    /**
     * Dispatch ws message
     * @param Server $server
     * @param Frame  $frame
     * @throws \Throwable
     */
    public function message(Server $server, Frame $frame): void
    {
        $fd = $frame->fd;
        /** @var Connection $conn */
        $conn = Session::mustGet();
        $info = $conn->getModule();

        // Want custom message handle, will don't trigger message parse and dispatch.
        $method = $info['eventMethods']['message'] ?? '';
        if ($method) {
            $class = $info['class'];
            \server()->log("conn#{$fd} call ws message handler '{$class}->{$method}'", [], 'debug');

            /** @var WsModuleInterface $module */
            $module = Container::$instance->getSingleton($class);
            $module->$method($server, $frame);
            return;
        }

        // Use swoft message dispatcher
        $parseClass = $info['messageParser'] ?? TextParser::class;
        /** @var MessageParserInterface $msgParser */
        $msgParser = Container::$instance->getSingleton($parseClass);
        if (!$msgParser) {
            throw new WsServerException("message parser bean '$parseClass' is not exists");
        }

        try {
            $body = $msgParser->decode($frame->data);
        } catch (\Throwable $e) {
            throw new WsMessageParseException("parse message error '{$e->getMessage()}", 500, $e);
        }

        $cmd  = $body['cmd'] ?? $info['defaultCommand'];
        $data = $body['data'] ?? null;

        /** @var Router $router */
        $router = \Swoft::getBean('wsRouter');
        [$status, $handler] = $router->matchCommand($info['path'], $cmd);
        if ($status === Router::NOT_FOUND) {
            throw new WsMessageException("message command '$cmd' is not found, in module path {$info['path']}");
        }

        [$ctlClass, $ctlMethod] = $handler;
        \server()->log("conn#{$fd} call ws message handler '{$ctlClass}->{$ctlMethod}'", $body, 'debug');

        $controller = Container::$instance->get($ctlClass);
        $controller->$ctlMethod($data);
        // TODO handle result...
    }

    /**
     * Dispatch ws close handle
     * @param Server $server
     * @param int    $fd
     */
    public function close(Server $server, int $fd): void
    {
        /** @var Connection $conn */
        $conn = Session::mustGet();
        $info = $conn->getModule();

        $method = $info['eventMethods']['close'] ?? '';
        if ($method) {
            $class = $info['class'];
            \server()->log("conn#{$fd} call ws close handler '{$class}->{$method}'", [], 'debug');

            /** @var WsModuleInterface $module */
            $module = Container::$instance->getSingleton($class);
            $module->$method($server, $fd);
        }
    }
}
