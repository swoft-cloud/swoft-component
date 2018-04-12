<?php

namespace SwoftTest\Redis;

use Swoft\App;
use Swoft\Redis\Pool\Config\RedisPoolConfig;
use Swoft\Redis\Redis;

class RedisPrefixTest extends AbstractTestCase
{
    /** @var \Swoft\Redis\Redis $redis * */
    private $redis = null;

    public function setUp()
    {
        parent::setUp();
        $this->redis = new Redis();
    }

    /**
     * @test
     * @requires extension redis
     */
    public function set()
    {
        $key    = 'set-key';
        $value  = 'set-value';
        $result = $this->redis->set($key, $value);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function setCo()
    {
        $key   = 'set-key';
        $value = 'set-value';
        $key   = $this->setCoName($key);
        $value = $this->setCoName($value);

        go(function () use ($key, $value) {
            $result = $this->redis->set($key, $value);
            $this->assertTrue($result);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function get()
    {
        $key    = 'set-key';
        $value  = 'set-value';
        $result = $this->redis->get($key);
        $this->assertSame($result, $value);
    }

    /**
     * @test
     */
    public function getCo()
    {
        $key   = 'set-key';
        $value = 'set-value';
        $key   = $this->setCoName($key);
        $value = $this->setCoName($value);
        go(function () use ($key, $value) {
            $result = $this->redis->get($key);
            $this->assertSame($result, $value);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function getSet()
    {
        $key    = 'getSet-key';
        $value1 = 'getSetValue';
        $this->redis->set($key, $value1);
        $value   = 'getSet-value';
        $result1 = $this->redis->getSet($key, $value);
        $this->assertSame($result1, $value1);
        $result = $this->redis->get($key);
        $this->assertSame($result, $value);
    }

    /**
     * @test
     */
    public function getSetCo()
    {
        $key    = 'getSet-key';
        $value  = 'getSet-value';
        $value2 = 'getSetValue';
        $key    = $this->setCoName($key);
        $value  = $this->setCoName($value);
        $value2 = $this->setCoName($value2);
        go(function () use ($key, $value, $value2) {
            $this->redis->set($key, $value);
            $result = $this->redis->getSet($key, $value2);
            $this->assertSame($result, $value);
            $result = $this->redis->get($key);
            $this->assertSame($result, $value2);

            $this->del();
        });
    }

    public function del()
    {
        $key = 'getSet-key';
        $key = $this->setCoName($key);
        go(function () use ($key) {
            $result = $this->redis->delete($key);
            $this->assertTrue($result);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function keys()
    {
        $result = $this->redis->getKeys('*');
        /* @var \Swoft\Redis\Pool\Config\RedisPoolConfig $redisConfig */
        $redisConfig = App::getBean(RedisPoolConfig::class);
        $prefix      = $redisConfig->getPrefix();
        foreach ($result as $key) {
            $this->assertStringStartsWith($prefix, $key);
        }
    }

    /**
     * @test
     * @requires extension redis
     */
    public function mGet()
    {
        $key1   = 'getSet-key';
        $key2   = 'set-key';
        $expect = [
            'getSet-value',
            'set-value'
        ];
        $result = $this->redis->mget([$key1, $key2]);
        $this->assertArraySubset($result, $expect);
    }

    /**
     * @test
     */
    public function mSetCo()
    {
        $key1   = 'mSet-key1';
        $value1 = 'mSet-Value1';
        $key1   = $this->setCoName($key1);
        $value1 = $this->setCoName($value1);
        $key2   = 'mset-key2';
        $value2 = 'mSet-Value2';
        $key2   = $this->setCoName($key2);
        $value2 = $this->setCoName($value2);

        $array = [
            $key1 => $value1,
            $key2 => $value2
        ];

        $expect = [
            $value1,
            $value2
        ];

        go(function () use ($array, $expect) {
            $result = $this->redis->mset($array);
            $this->assertTrue($result);
            $keys   = array_keys($array);
            $result = $this->redis->mget($keys);
            $this->assertArraySubset($result, $expect);
        });
    }

    /**
     * @test
     */
    public function sAddsMoveCo()
    {
        $key1    = $this->setCoName('sAddsMove-key1');
        $value10 = $this->setCoName('member11');
        $value11 = $this->setCoName('member12');
        $value12 = $this->setCoName('member13');

        $key2    = $this->setCoName('sAddsMove-key2');
        $value20 = $this->setCoName('member21');
        $value21 = $this->setCoName('member22');

        $expect1 = [
            $value10,
            $value11,
        ];

        $expect2 = [
            $value12,
            $value20,
            $value21,
        ];

        go(function () use ($key1, $key2, $value10, $value11, $value12, $value20, $value21, $expect1, $expect2) {
            $result = $this->redis->sAdd($key1, $value10);
            $this->assertEquals(1, $result);
            $result = $this->redis->sAdd($key1, $value11);
            $this->assertEquals(1, $result);
            $result = $this->redis->sAdd($key1, $value12);
            $this->assertEquals(1, $result);

            $result = $this->redis->sAdd($key2, $value20);
            $this->assertEquals(1, $result);
            $result = $this->redis->sAdd($key2, $value21);
            $this->assertEquals(1, $result);

            $result = $this->redis->sMove($key1, $key2, $value12);
            $this->assertEquals(1, $result);

            $result = $this->redis->sMembers($key1);

            foreach ($result as $value) {
                $this->assertContains($value, $expect1);
            }

            $result = $this->redis->sMembers($key2);

            foreach ($result as $value) {
                $this->assertContains($value, $expect2);
            }

            $this->delsadd($key1, $key2);
        });
    }

    public function delsadd($key1, $key2)
    {
        go(function () use ($key1, $key2) {
            $result = $this->redis->delete($key1);
            $this->assertTrue($result);
            $result = $this->redis->delete($key2);
            $this->assertTrue($result);
        });
    }
}