<?php declare(strict_types=1);


namespace SwoftTest\Component\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\BF;
use SwoftTest\Component\Testing\Aop\ZeroAop;

class ZeroAopTest extends TestCase
{
    public function testZero(): void
    {
        /* @var ZeroAop $bean */
        $bean   = BF::getBean(ZeroAop::class);
        $result = $bean->returnZero();
        $this->assertTrue($result === 0);
    }

    public function testZero2(): void
    {
        /* @var ZeroAop $bean */
        $bean   = BF::getBean(ZeroAop::class);
        $result = $bean->afterZero();
        $this->assertTrue($result === 0);
    }
}