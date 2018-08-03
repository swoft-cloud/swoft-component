<?php

namespace SwoftTest\Redis;

/**
 * ZsetTest
 */
class ZsetTest extends AbstractTestCase
{
    public function testZadd()
    {
        $key = uniqid();
        $ret = $this->redis->zAdd($key, 1.1, 'key');
        $this->assertEquals($ret, 1);

        $ret2 = $this->redis->zAdd($key, 1.3, 'key2');
        $this->assertEquals($ret2, 1);

        $ret3 = $this->redis->zAdd($key, 3.2, 'key3');
        $this->assertEquals($ret3, 1);

        $ret4 = $this->redis->zAdd($key, 1.2, 'key4');
        $this->assertEquals($ret4, 1);

        $ret5 = $this->redis->zAdd($key, 5.2, 'key5');
        $this->assertEquals($ret5, 1);

        $keys = $this->redis->zRange($key, 0, -1);
        $this->assertCount(5, $keys);

        $data = [
            'key4',
            'key2',
            'key3',
        ];
        $rangeKeys = $this->redis->zRangeByScore($key, 1.2, 3.2);
        $this->assertEquals($data, $rangeKeys);

        $data2 = [
            'key4' => 1.2,
            'key2' => 1.3,
            'key3' => 3.2,
        ];
        $rangeKeys = $this->redis->zRange($key, 1.2, 3.2, 'WITHSCORES');
        $this->assertEquals($data2, $rangeKeys);

        $rangeKeys = $this->redis->zRange($key, 1.2, 3.2, false);
        $this->assertEquals($data, $rangeKeys);

        $rangeKeys = $this->redis->zRange($key, 1.2, 3.2, true);
        $this->assertEquals($data2, $rangeKeys);

        $rangeKeys = $this->redis->zRange($key, 1.2, 3.2, 0);
        $this->assertEquals($data, $rangeKeys);

        $rangeKeys = $this->redis->zRange($key, 1.2, 3.2, 'xxx');
        $this->assertEquals($data2, $rangeKeys);

        /** @var \Redis $redis */
        $redis = $this->redis;
        $rangeKeys = $redis->zRangeByScore($key, 1, 2, [
            'limit' => [1, 1]
        ]);
        $this->assertEquals(['key4'], $rangeKeys);

        $rangeKeys = $redis->zRangeByScore($key, 1, 2, [
            'withscores' => true,
            'limit' => [1, 1]
        ]);
        $this->assertEquals(['key4' => 1.2], $rangeKeys);

        $rangeKeys = $redis->zRangeByScore($key, 1.2, 3.2, [
            'withscores' => true
        ]);
        $this->assertEquals($data2, $rangeKeys);

        $rangeKeys = $redis->zRevRangeByScore($key, 2, 1, [
            'limit' => [0, 1]
        ]);
        $this->assertEquals(['key2'], $rangeKeys);

        $rangeKeys = $redis->zRevRangeByScore($key, 2, 1, [
            'limit' => [0, 1],
            'withscores' => true
        ]);
        $this->assertEquals(['key2' => 1.3], $rangeKeys);

        $rangeKeys = $redis->zRevRangeByScore($key, 3.2, 1.2, [
            'withscores' => true
        ]);
        $this->assertEquals(['key3' => 3.2, 'key2' => 1.3, 'key4' => 1.2], $rangeKeys);
    }

    public function testZaddByCo()
    {
        go(function () {
            $this->testZadd();
        });
    }
}