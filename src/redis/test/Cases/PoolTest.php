<?php

namespace SwoftTest\Redis\Cases;

use Swoft\App;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Redis;
use SwoftTest\Redis\Testing\Pool\RedisEnvPoolConfig;
use SwoftTest\Redis\Testing\Pool\RedisPptPoolConfig;
use SwoftTest\Redis\Testing\Clients\TimeoutRedis;
use SwoftTest\Redis\Testing\Pool\TimeoutPool;

/**
 * PoolTest
 */
class PoolTest extends AbstractTestCase
{
    public function testRedisPoolPpt()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(RedisPptPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'redis');
        // $this->assertEquals($pConfig->getUri(), [
        //     'tcp://127.0.0.1:6379',
        //     'tcp://127.0.0.1:6379',
        // ]);
        $this->assertEquals($pConfig->getMaxActive(), 10);
        $this->assertEquals($pConfig->getMaxWait(), 20);
        $this->assertEquals($pConfig->getMaxWaitTime(), 3);
        $this->assertEquals($pConfig->getMaxIdleTime(), 60);
        $this->assertEquals($pConfig->getTimeout(), 3);
    }

    public function testRedisPoolEnv()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(RedisEnvPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'redis');
        $this->assertEquals($pConfig->getProvider(), 'consul');
        $this->assertEquals($pConfig->getTimeout(), 3);
        // $this->assertEquals($pConfig->getUri(), [
        //     'tcp://127.0.0.1:6379',
        //     'tcp://127.0.0.1:6379',
        // ]);
        $this->assertEquals($pConfig->getBalancer(), 'random');
        $this->assertEquals($pConfig->getMaxActive(), 10);
        $this->assertEquals($pConfig->getMaxWait(), 20);
        $this->assertEquals($pConfig->getMaxWaitTime(), 3);
        $this->assertEquals($pConfig->getMaxIdleTime(), 60);
        $this->assertEquals($pConfig->getTimeout(), 3);
    }

    public function testRedisTimeout()
    {
        $redis = bean(TimeoutRedis::class);
        $btime = microtime(true);
        try {
            $redis->exists('not_connected');
        } catch (\Throwable $ex) {
            if (App::isCoContext()) {
                $this->assertInstanceOf(RedisException::class, $ex);
                $this->assertEquals('Redis connection failure host=echo.swoft.org port=6379', $ex->getMessage());
            } else {
                $this->assertInstanceOf(\RedisException::class, $ex);
                $this->assertEquals('Operation timed out', $ex->getMessage());
            }
            $this->assertTrue(microtime(true) - $btime < 2);
        }
    }

    public function testRedisReconnectSelectDb()
    {
        $redis = bean(Redis::class);
        $redis->set('test_select_db', 1);

        $redis->reconnect();

        $res = $redis->get('test_select_db');

        $this->assertEquals(1, $res);
    }
}