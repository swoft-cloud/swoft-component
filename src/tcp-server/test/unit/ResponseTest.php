<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Tcp\Server\Response;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    public function testBasic(): void
    {
        $w = Response::new(22);

        $this->assertFalse($w->isSent());
        $this->assertSame(22, $w->getFd());
        $this->assertSame(22, $w->getReqFd());

        $w->setFd(12);
        $this->assertSame(12, $w->getFd());

        $w->setSent(true);
        $this->assertTrue($w->isSent());

        $this->assertTrue($w->isEmpty());
        $w->setContent('hi');
        $this->assertFalse($w->isEmpty());
        $w->setCode(23);
        $w->setContent('');
        $this->assertFalse($w->isEmpty());

        $this->assertSame('OK', $w->getMsg());
    }
}
