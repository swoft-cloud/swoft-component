<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 17:25
 */

namespace SwoftTest\WebSocket\Server\Fixture;

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Contract\RequestHandlerInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class ChatModule
 * @package SwoftTest\WebSocket\Server\Fixture
 */
class ChatModule implements RequestHandlerInterface
{
    /**
     * 在这里你可以验证握手的请求信息
     * - 必须返回含有两个元素的array
     *  - 第一个元素的值来决定是否进行握手
     *  - 第二个元素是response对象
     * - 可以在response设置一些自定义header,body等信息
     * @param Request  $request
     * @param Response $response
     * @return array
     * [
     *  self::HANDSHAKE_OK,
     *  $response
     * ]
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        // TODO: Implement checkHandshake() method.
    }

    /**
     * @param Server  $server
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Server $server, Request $request, int $fd): void
    {
        // TODO: Implement onOpen() method.
    }

    /**
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.
    }

    /**
     * on connection closed
     * - you can do something. eg. record log
     * @param Server $server
     * @param int    $fd
     * @return mixed
     */
    public function onClose(Server $server, int $fd)
    {
        // TODO: Implement onClose() method.
    }

    /*****************************************************************************
     * handle message commands
     ****************************************************************************/


}
