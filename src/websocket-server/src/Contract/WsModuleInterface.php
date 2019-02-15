<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Contract;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Interface WsModuleInterface
 * @since 2.0
 */
interface WsModuleInterface
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
}
