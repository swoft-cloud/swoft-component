<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 18:02
 */

namespace Swoft\WebSocket\Server\Contract;

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoole\WebSocket\Server;

/**
 * Interface ModuleInterface
 * @package Swoft\WebSocket\Server\Contract
 */
interface ModuleInterface
{
    // accept or reject for handshake
    public const ACCEPT = 1;
    public const REJECT = 2;

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
    public function checkHandshake(Request $request, Response $response): array;

    /**
     * @param Server  $server
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Server $server, Request $request, int $fd): void;

    /**
     * on connection closed
     * - you can do something. eg. record log
     * @param Server $server
     * @param int    $fd
     * @return mixed
     */
    public function onClose(Server $server, int $fd);
}
