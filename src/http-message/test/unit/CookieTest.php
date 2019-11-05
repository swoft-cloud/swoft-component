<?php declare(strict_types=1);

namespace SwoftTest\Http\Message\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Cookie;

class CookieTest extends TestCase
{
    public function testCookie(): void
    {
        $c = new Cookie();
        $this->assertSame('', $c->toString());

        $c->setName('ck')->setValue('val')->setDomain('abc.com')->setExpires(60);
        $this->assertSame('ck', $c->getName());
        $this->assertSame('val', $c->getValue());
        $this->assertSame('abc.com', $c->getDomain());
        $this->assertSame(60, $c->getExpires());

        $c->setSecure(true)->setHostOnly(true)->setHttpOnly(true);
        $this->assertTrue($c->isSecure());
        $this->assertTrue($c->isHostOnly());
        $this->assertTrue($c->isHttpOnly());

        $c->delete();
        $this->assertSame('', $c->getValue());
        $this->assertSame(-60, $c->getExpires());

        $this->assertNotEmpty($c->toArray());
        $this->assertNotEmpty($c->toString());
    }
}
