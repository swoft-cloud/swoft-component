<?php declare(strict_types=1);


namespace SwoftTest\Log\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Log\Helper\Log;

/**
 * Class LogTest
 *
 * @since 2.0
 */
class LogTest extends TestCase
{
    public function testA()
    {
        $result = Log::formatLog('message%s%s%s', 'a', 'b', 'c');
        $this->assertEquals($result, ['messageabc', []]);

        $result = Log::formatLog('message%s');
        $this->assertEquals($result, ['message%s', []]);

        $result = Log::formatLog('message%s', ['a' => 'b']);
        $this->assertEquals($result, ['message%s', ['a' => 'b']]);
    }
}