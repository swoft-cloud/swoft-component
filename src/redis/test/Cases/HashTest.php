<?php

namespace SwoftTest\Redis;

/**
 * HashTest
 */
class HashTest extends AbstractTestCase
{
    public function testHmsetAndHmget()
    {
        $key    = uniqid();
        $result = $this->redis->hMset($key, ['key' => 'value', 'key2' => 'value2', 'key3' => 'value3']);
        $this->assertEquals(true, $result);

        $data   = [
            'key2' => 'value2',
            'key'  => 'value',
        ];
        $values = $this->redis->hMGet($key, ['key2', 'key']);
        $this->assertEquals($data, $values);

        $data   = [
            'NotExistKey'  => false,
            'NotExistKey2' => false,
        ];
        $values = $this->redis->hMGet($key, ['NotExistKey', 'NotExistKey2']);
        $this->assertEquals($data, $values);

        $this->redis->set($key, 'xxxxx');
        $result = $this->redis->hMGet($key,['key']);
        $this->assertFalse($result);

        $this->redis->delete($key);
        $result = $this->redis->hMGet($key, ['key']);
        $this->assertEquals(['key' => false], $result);

        $this->redis->sAdd($key, 'xxxxx');
        $result = $this->redis->hMGet($key, ['key']);
        $this->assertFalse($result);
    }

    public function testHmsetAndHmgetByCo()
    {
        go(function () {
            $this->testHmsetAndHmget();
        });
    }

    public function testHGetAll()
    {
        $key = uniqid();
        $result = $this->redis->hMset($key, ['key' => 'value', 'key2' => 'value2', 'key3' => 'value3']);
        $this->assertEquals(true, $result);

        $result = $this->redis->hGetAll($key);
        $this->assertEquals(['key' => 'value', 'key2' => 'value2', 'key3' => 'value3'], $result);

        $this->redis->set($key, 'xxxxx');
        $result = $this->redis->hGetAll($key);
        $this->assertFalse($result);

        $this->redis->delete($key);
        $result = $this->redis->hGetAll($key);
        $this->assertEquals([], $result);

        $this->redis->sAdd($key, 'xxxxx');
        $result = $this->redis->hGetAll($key);
        $this->assertFalse($result);
    }

    public function testHGetAllByCo()
    {
        go(function () {
            $this->testHGetAll();
        });
    }

    public function testHIncrBy()
    {
        $key = uniqid();
        /** @var \Redis $redis */
        $redis = $this->redis;
        $result = $redis->hIncrBy($key, 'incr', 2);
        $this->assertEquals(2, $result);
        $result = $redis->hIncrBy($key, 'incr', 2);
        $this->assertEquals(4, $result);
        $result = $redis->hGet($key, 'incr');
        $this->assertEquals(4, $result);
    }

    public function testHIncrByCo()
    {
        go(function () {
            $this->testHIncrBy();
        });
    }

    public function testHSetNx()
    {
        $key = uniqid();
        /** @var \Redis $redis */
        $redis = $this->redis;
        $result = $redis->hSetNx($key, 'one', 1);
        $this->assertTrue($result);

        $result = $redis->hSetNx($key, 'one', 1);
        $this->assertFalse($result);
    }

    public function testHSetNxByCo()
    {
        go(function () {
            $this->testHSetNx();
        });
    }

    public function testHDel()
    {
        $key = uniqid();
        /** @var \Redis $redis */
        $redis = $this->redis;
        $result = $redis->hSetNx($key, 'one', 1);
        $this->assertTrue($result);
        $result = $redis->hSetNx($key, 'two', 2);
        $this->assertTrue($result);
        $result = $redis->hSetNx($key, 'three', 3);
        $this->assertTrue($result);

        $result = $redis->hDel($key, 'one', 'two');
        $this->assertEquals(2, $result);
        $result = $redis->hGetAll($key);
        $this->assertEquals(['three' => 3], $result);
    }

    public function testHDelByCo()
    {
        go(function () {
            $this->testHDel();
        });
    }

    public function testHLen()
    {
        $key = uniqid();
        /** @var \Redis $redis */
        $redis = $this->redis;
        $result = $redis->hSetNx($key, 'one', 1);
        $this->assertTrue($result);
        $result = $redis->hSetNx($key, 'two', 2);
        $this->assertTrue($result);
        $result = $redis->hSetNx($key, 'three', 3);
        $this->assertTrue($result);

        $result = $redis->hLen($key);
        $this->assertEquals(3, $result);
    }

    public function testHLenByCo()
    {
        go(function () {
            $this->testHLen();
        });
    }

    public function testHExists()
    {
        $key = uniqid();
        /** @var \Redis $redis */
        $redis = $this->redis;
        $result = $redis->hSetNx($key, 'one', 1);
        $this->assertTrue($result);

        $result = $redis->hExists($key, 'one');
        $this->assertTrue($result);
        $result = $redis->hExists($key, 'two');
        $this->assertFalse($result);
    }

    public function testHExistsByCo()
    {
        go(function () {
            $this->testHExists();
        });
    }

    public function testHValsAndHKeys()
    {
        $key = uniqid();
        /** @var \Redis $redis */
        $redis = $this->redis;
        $result = $redis->hMset($key, ['one' => 1, 'two' => 'hello', 'three' => 'world']);
        $this->assertTrue($result);

        $result = $redis->hKeys($key);
        $this->assertEquals(['one', 'two', 'three'], $result);

        $result = $redis->hVals($key);
        $this->assertEquals([1, 'hello', 'world'], $result);
    }

    public function testHValsAndHKeysByCo()
    {
        go(function () {
            $this->testHValsAndHKeys();
        });
    }
}