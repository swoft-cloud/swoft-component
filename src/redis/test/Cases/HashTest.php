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
}