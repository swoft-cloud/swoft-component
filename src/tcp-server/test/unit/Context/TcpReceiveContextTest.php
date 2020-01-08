<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Unit\Context;

use PHPUnit\Framework\TestCase;
use Swoft\Tcp\Server\Context\TcpReceiveContext;
use Swoft\Tcp\Server\Request;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;

/**
 * Class TcpReceiveContextTest
 */
class TcpReceiveContextTest extends TestCase
{
    public function testBasic(): void
    {
        $req = Request::new(2, 'data', 3);
        $res = new MockTcpResponse();

        $ctx = TcpReceiveContext::new(2, $req, $res);

        $this->assertSame(2, $ctx->getFd());
        $this->assertSame(3, $ctx->getRequest()->getReactorId());
        $this->assertSame('data', $ctx->getRequest()->getRawData());
    }
}
