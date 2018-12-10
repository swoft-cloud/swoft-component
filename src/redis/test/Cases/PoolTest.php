<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Redis\Cases;

use Swoft\App;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Redis;
use SwoftTest\Redis\Testing\Clients\TimeoutRedis;
use SwoftTest\Redis\Testing\Pool\RedisEnvPoolConfig;
use SwoftTest\Redis\Testing\Pool\RedisPptPoolConfig;

/**
 * PoolTest
 */
class PoolTest extends AbstractTestCase
{
    public function testRedisPoolPpt()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(RedisPptPoolConfig::class);
        $this->assertSame($pConfig->getName(), 'redis');
        $this->assertEquals($pConfig->getUri(), [
            'tcp://127.0.0.1:6379',
            'tcp://127.0.0.1:6379',
        ]);
        $this->assertSame($pConfig->getMaxActive(), 10);
        $this->assertSame($pConfig->getMaxWait(), 20);
        $this->assertSame($pConfig->getMaxWaitTime(), 3);
        $this->assertSame($pConfig->getMaxIdleTime(), 60);
        $this->assertSame($pConfig->getTimeout(), 3.0);
    }

    public function testRedisPoolEnv()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(RedisEnvPoolConfig::class);
        $this->assertSame($pConfig->getName(), 'redis');
        $this->assertSame($pConfig->getProvider(), 'consul');
        $this->assertSame($pConfig->getTimeout(), 3.0);
        $this->assertEquals($pConfig->getUri(), [
            'tcp://127.0.0.1:6379',
            'tcp://127.0.0.1:6379',
        ]);
        $this->assertSame($pConfig->getBalancer(), 'random');
        $this->assertSame($pConfig->getMaxActive(), 10);
        $this->assertSame($pConfig->getMaxWait(), 20);
        $this->assertSame($pConfig->getMaxWaitTime(), 3);
        $this->assertSame($pConfig->getMaxIdleTime(), 60);
        $this->assertSame($pConfig->getTimeout(), 3.0);
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
                $this->assertSame('Redis connection failure host=echo.swoft.org port=6379', $ex->getMessage());
            } else {
                // TODO: 单元测试内会返回 PHPUnit_Framework_Error_Warning
                // $this->assertInstanceOf(\RedisException::class, $ex);
                $this->assertSame('Operation timed out', $ex->getMessage());
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

        $this->assertSame(1, $res);
    }
}
