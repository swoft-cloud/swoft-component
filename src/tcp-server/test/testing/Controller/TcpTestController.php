<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Testing\Controller;

use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;
use SwoftTest\Tcp\Server\Testing\Middleware\User1Middleware;
use SwoftTest\Tcp\Server\Testing\Middleware\User2Middleware;

/**
 * Class TcpTestController
 *
 * @TcpController(middlewares={User1Middleware::class})
 */
class TcpTestController
{
    /**
     * @TcpMapping()
     */
    public function index(): void
    {

    }

    /**
     * @TcpMapping(middlewares={User2Middleware::class})
     */
    public function test(): void
    {

    }
}
