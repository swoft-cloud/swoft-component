<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Context\Context;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\Exception\WsMessageException;
use Swoft\WebSocket\Server\Exception\WsMessageParseException;
use Swoft\WebSocket\Server\Exception\WsMessageRouteException;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;
use Swoft\WebSocket\Server\Message\Message;
use Swoft\WebSocket\Server\MessageParser\RawTextParser;
use Swoft\WebSocket\Server\Message\Response as MessageResponse;
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
     * @throws \Swoft\WebSocket\Server\Exception\WsModuleRouteException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function handshake(Request $request, Response $response): array
    {
        /** @var Router $router */
        $router = BeanFactory::getSingleton('wsRouter');

        /** @var Connection $conn */
        $conn = Session::mustGet();
        $path = $request->getUriPath();

        if (!$info = $router->match($path)) {
            throw new WsModuleRouteException(\sprintf(
                'The requested websocket route path "%s" is not exist!',
                $path
            ));
        }

        $class = $info['class'];
        $conn->setModuleInfo($info);

        \server()->log("Handshake: found handler for path '$path', ws module class is $class", [], 'debug');

        /** @var WsModuleInterface $module */
        $module = BeanFactory::getSingleton($class);

        // Call user method
        if ($method = $info['eventMethods']['handshake'] ?? '') {
            return $module->$method($request, $response);
        }

        // Auto handshake
        return [true, $response->withAddedHeader('swoft-ws-handshake', 'auto')];
    }

    /**
     * Dispatch ws close handle
     * @param Server $server
     * @param int    $fd
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function close(Server $server, int $fd): void
    {
        /** @var Connection $conn */
        $conn = Session::mustGet();
        $info = $conn->getModuleInfo();

        $method = $info['eventMethods']['close'] ?? '';
        if (!$method) {
            return;
        }

        $class = $info['class'];
        \server()->log("conn#{$fd} call ws close handler '{$class}::{$method}'", [], 'debug');

        /** @var WsModuleInterface $module */
        $module = BeanFactory::getSingleton($class);
        $module->$method($server, $fd);
    }
}
