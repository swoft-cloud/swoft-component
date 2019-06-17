<?php declare(strict_types=1);


namespace SwoftTest\Redis\Unit\Command;


use Swoft\Redis\Redis;
use SwoftTest\Redis\Unit\TestCase;

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

        // zAdd 12 key1 14 key3 16 key4
        $result = Redis::zAdd($key, $scores);

        $this->assertEquals($result, \count($scores));

    }

    public function testKeyScoresAdd()
    {
        $scores1 = [
            'key1' => 11,
            'key2' => 21,
        ];

        $scores = [
            9  => 'key1',
            10 => 'key2',
        ];
        // zAdd 12.1 key1 12.3 key2
        $result1 = Redis::zAdd(\uniqid(), $scores1);
        $result  = Redis::zAdd(\uniqid(), $scores);

        $this->assertEquals($result1, $result);

    }

    public function testZrem()
    {
        $key    = __FUNCTION__;
        $scores = [
            12.2 => 'key1',
            14.2 => 'key3',
            16.9 => 'key4',
        ];

        Redis::zAdd($key, $scores);
        $res = Redis::zRem($key, $key, 'key1');
        $this->assertEquals(1, $res);
    }

    public function testRange()
    {
        $key = __FUNCTION__ . 'hah';
        Redis::del($key);
        // Floating point is not supported Automatic conversion integer
        $scores = [
            12.0 => 'key1',
            14.0 => 'key3',
            16.0 => 'key4',
        ];

        Redis::zAdd($key, $scores);
        // return  value=>key
        // command=  ZRANGEBYSCORE $key -inf +inf WITHSCORES
        $res = Redis::zRangeByScore($key, '-inf', '+inf', ['withscores' => true]);
        // command=  ZRANGEBYSCORE $key 12 17 WITHSCORES
        $res1 = Redis::zRangeByScore($key, '12', '17', ['withscores' => true, 'limit' => [0, 3]]);
        $res2 = Redis::zRangeByScore($key, '-inf', '17', ['withscores' => true]);
        $res3 = Redis::zRangeByScore($key, '12', '+inf', ['withscores' => true]);

        $this->assertEquals($res, $res1);
        $this->assertEquals($res, $res2);
        $this->assertEquals($res, $res3);

        $this->assertEquals(\count($scores), \count($res));

        foreach ($res as $key => $v) {
            $this->assertTrue(in_array($key, $scores));
            $this->assertTrue(in_array($v, array_keys($scores)));
        }
    }
}
