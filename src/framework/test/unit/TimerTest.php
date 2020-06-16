<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Co;
use Swoft\Context\Context;
use Swoft\Exception\SwoftException;
use Swoft\Timer;
use SwoftTest\Testing\TestContext;

/**
 * Class TimerTest
 *
 * @since 2.0
 */
class TimerTest extends TestCase
{
    /**
     * @var int
     */
    private $tick = 0;

    /**
     * @var int
     */
    private $after = 0;

    /**
     */
    public function setUp()
    {
        Context::set(TestContext::new());
    }

    /**
     * @throws SwoftException
     */
    public function testTick()
    {
        $a   = 1;
        $tid = Timer::tick(500, function ($a) {
            $this->tick++;
        }, $a);

        Co::sleep(1);
        Timer::clear($tid);

        $this->assertEquals($this->tick, 2);
    }

    /**
     * @throws SwoftException
     */
    public function testAfter()
    {
        $a = 1;
        Timer::after(500, function ($a) {
            $this->after = $a;
        }, $a);

        Co::sleep(1);

        $this->assertEquals($this->after, 1);
    }
}
