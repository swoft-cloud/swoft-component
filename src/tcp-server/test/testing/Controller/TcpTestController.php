<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Testing\Controller;

use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;
use Swoft\Tcp\Server\Response;
use SwoftTest\Tcp\Server\Testing\Middleware\User1Middleware;
use SwoftTest\Tcp\Server\Testing\Middleware\User2Middleware;
use SwoftTest\Testing\TempArray;

/**
 * Class TcpTestController
 *
 * @TcpController(middlewares={User1Middleware::class})
 */
class TcpTestController
{
    /**
     * @TcpMapping()
     * @param Response $response
     */
    public function index(Response $response): void
    {
        TempArray::set(__METHOD__, 'hello');
        $response->setContent($response->getContent() . '[INDEX]');
    }

    /**
     * @TcpMapping(middlewares={User2Middleware::class})
     * @param Response $response
     */
    public function test(Response $response): void
    {
        TempArray::set(__METHOD__, 'hello');
        $response->setContent($response->getContent() . '[TEST]');
    }
}
