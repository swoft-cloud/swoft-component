<?php

namespace SwoftTest\Redis;

use Swoft\App;
use Swoole\Coroutine\Redis;

/**
 * StringTest
 */
class StringTest extends AbstractTestCase
{
    public function testSet()
    {
        $value = uniqid();
        $key = 'stringKey';
        if (App::isCoContext()) {
            $key .= 'co';
        }

        $nottlResult = $this->redis->set($key . 'key2', uniqid());
        $this->assertTrue($nottlResult);

        $result = $this->redis->set($key, $value, 100);
        $this->assertTrue($result);

        $ttl = $this->redis->ttl($key);
        $this->assertGreaterThan(1, $ttl);

        $getValue = $this->redis->get($key);
        $this->assertEquals($getValue, $value);
    }

    public function testSetByCo()
    {
        go(function () {
            $this->testSet();
        });
    }

    public function testGet()
    {
        $default = 'defualtValue';
        $result = $this->redis->get("notKey" . uniqid(), $default);
        $this->assertSame($result, $default);
    }

    public function testGetByCo()
    {
        go(function () {
            $this->testGet();
        });
    }

    public function testMsetAndMget()
    {
        $key = uniqid();
        $key2 = uniqid();
        $value = 'value1';
        $value2 = 'val2';

        $result = $this->redis->mset([
            $key => $value,
            $key2 => $value2,
        ]);

        $this->assertTrue($result);

        $values = $this->redis->mget([$key2, $key]);
        $this->assertEquals($values[$key], $value);
        $this->assertEquals($values[$key2], $value2);
    }

    public function testMsetAndMgetByCo()
    {
        go(function () {
            $this->testMsetAndMget();
        });
    }

    public function testHyperLoglog()
    {
        $this->redis->delete('pf:test');
        $this->redis->delete('pf:test2');
        $this->redis->delete('pf:test3');

        $result = $this->redis->pfAdd('pf:test', [1, 2, 3]);

        $this->assertEquals(1, $result);

        $result = $this->redis->pfCount('pf:test');
        $this->assertEquals(3, $result);

        $result = $this->redis->pfAdd('pf:test2', [3, 4, 5]);
        $this->assertEquals(1, $result);

        $result = $this->redis->pfMerge('pf:test3', ['pf:test', 'pf:test2']);
        $this->assertTrue($result);

        $result = $this->redis->pfCount('pf:test3');
        $this->assertEquals(5, $result);

        $result = $this->redis->pfCount(['pf:test', 'pf:test2']);
        $this->assertEquals(5, $result);
    }

    public function testHyperLoglogByCo()
    {
        go(function () {
           $this->testHyperLoglog();
        });
    }
}