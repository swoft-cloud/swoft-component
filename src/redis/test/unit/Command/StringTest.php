<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Redis\Unit\Command;

use Swoft\Redis\Redis;
use SwoftTest\Redis\Unit\RedisTestCase;

/**
 * Class StringTest
 *
 * @since 2.0
 */
class StringTest extends RedisTestCase
{
    public function testSet(): void
    {
        $key    = $this->uniqId();
        $result = Redis::set($key, $this->uniqId());
        $this->assertTrue($result);

        $ttl    = 100;
        $ttlKey = $this->uniqId();
        Redis::set($ttlKey, $this->uniqId(), $ttl);

        $getTtl = Redis::ttl($ttlKey);
        $this->assertGreaterThan($ttl / 2, $getTtl);

        Redis::set($key, json_encode(['a']), 111);
        Redis::get($key);
    }

    public function testGet(): void
    {
        $value = $this->uniqId();
        $key   = $this->setKey($value);

        $getValue = Redis::get($key);

        $this->assertEquals($value, $getValue);
    }

    public function testArray(): void
    {
        $key = $this->uniqId();

        $setData = [
            'goods' => ['goods_id' => 1, 'goods_name' => 'iPhone xx']
        ];
        Redis::set($key, $setData);

        $this->assertEquals($setData, Redis::get($key));
    }

    public function testInc(): void
    {
        $key = $this->uniqId();

        $redis = Redis::connection('redis.inc.pool');

        $this->assertEquals(1, $redis->incrBy($key, 1));
        $redis->set($key, 2);
        $redis->incr($key);
        $this->assertEquals(3, $redis->get($key));
    }

    public function testMsetAndMget(): void
    {
        $key    = ':mset:' . $this->uniqId();
        $value  = [$this->uniqId()];
        $key2   = ':mset:' . $this->uniqId();
        $value2 = [$this->uniqId()];

        $keys = [
            $key  => $value,
            $key2 => $value2,
        ];

        $result = Redis::mset($keys);
        $this->assertTrue($result);


        $resultVlue  = Redis::get($key);
        $resultVlue2 = Redis::get($key2);

        $this->assertEquals($value, $resultVlue);
        $this->assertEquals($value2, $resultVlue2);

        $values = Redis::mget([$key, $key2, 'key3']);

        $this->assertEquals(count($values), 2);
        $this->assertEquals($values, $keys);
    }

    public function testBit(): void
    {
        Redis::setBit('user:sign' . date('ymd'), 16, false);
        $this->assertTrue(true);
    }
}
