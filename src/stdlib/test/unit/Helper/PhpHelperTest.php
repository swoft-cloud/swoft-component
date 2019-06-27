<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\PhpHelper;

class PhpHelperTest extends TestCase
{
    public function testCall()
    {
        $callable = function ($a){
            return $a;
        };

        $res = PhpHelper::call($callable, "hello");
        $this->assertSame("hello", $res);

        $obj = new class {
            public function say($a){
                return $a;
            }
        };

        $res = PhpHelper::call([$obj, 'say'], "hello");
        $this->assertSame("hello", $res);
    }
}
