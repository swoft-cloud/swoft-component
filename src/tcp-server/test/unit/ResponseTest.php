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

        $this->assertTrue($w->isEmpty());
        $w->setContent('hi');
        $this->assertFalse($w->isEmpty());
    }
}
