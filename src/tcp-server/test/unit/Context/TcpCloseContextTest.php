<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Unit\Context;

use PHPUnit\Framework\TestCase;
use Swoft\Tcp\Server\Context\TcpCloseContext;

class TcpCloseContextTest extends TestCase
{
    public function testBasic(): void
    {
        $ctx = TcpCloseContext::new(2, 3);

        $this->assertSame(2, $ctx->getFd());
        $this->assertSame(3, $ctx->getReactorId());
    }
}
