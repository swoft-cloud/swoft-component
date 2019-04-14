<?php declare(strict_types=1);


namespace SwoftTest\Redis\Command;


use Swoft\Redis\Redis;
use SwoftTest\Redis\TestCase;

/**
 * Class SortedSetTest
 *
 * @since 2.0
 */
class SortedSetTest extends TestCase
{
    public function testZadd()
    {
        $key    = \uniqid();
        $scores = [
            12.2 => 'key1',
            14.2 => 'key3',
            16.9 => 'key4',
        ];

        $result = Redis::zAdd($key, $scores);
        $this->assertEquals($result, \count($scores));
    }
}