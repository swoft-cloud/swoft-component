<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 13:01
 */

namespace Swoft\WebSocket\Server\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Http\Message\Request as Psr7Request;
use Swoft\Server\Swoole\HandShakeInterface;
use Swoft\WebSocket\Server\Event\WsEvent;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Server;

/**
 * Class HandShakeListener
 * @since 2.0
 * @package Swoft\WebSocket\Server\Swoole
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
     */
    public function onHandShake(Request $request, Response $response): bool
    {
        // TODO: Implement onHandShake() method.
    }

    /**
     * @param Server $server
     * @param Psr7Request $request
     * @param int $fd
     * @throws \InvalidArgumentException
     */
    public function onWsOpen(Server $server, Psr7Request $request, int $fd)
    {
        App::trigger(WsEvent::ON_OPEN, null, $server, $request, $fd);

        \server()->log("connection #$fd has been opened, co ID #" . Co::tid(), [], 'debug');

        /** @see Dispatcher::open() */
        \bean('wsDispatcher')->open($server, $request, $fd);
    }

}
