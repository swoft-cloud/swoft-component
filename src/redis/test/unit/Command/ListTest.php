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
use function sgo;

/**
 * Class ListTest
 *
 * @since 2.0
 */
class ListTest extends RedisTestCase
{
    /**
     *
     *
     * @return string
     */
    public function getListKey(): string
    {
        return __METHOD__;
    }

    public function testLpush(): void
    {
        $params = [1, 2, 34, 5];
        $res    = Redis::lPush($this->getListKey(), ...$params);
        $this->assertIsInt($res);
    }

    public function testLPop(): void
    {
        $param = __METHOD__;
        Redis::lPush($this->getListKey(), $param);
        $res = Redis::lPop($this->getListKey());

        $this->assertEquals($res, $param);
    }

    public function testRpop(): void
    {
        $param = __METHOD__;
        Redis::rPush($this->getListKey(), $param);
        $res = Redis::rPop($this->getListKey());

        $this->assertEquals($res, $param);
    }

    public function testCount(): void
    {
        Redis::lPushx($this->getListKey(), 1);
        $res = Redis::lLen($this->getListKey());

        $this->assertGreaterThan(0, $res);
    }

    public function testInsert(): void
    {
        Redis::del($this->getListKey());
        $value = 'yes';
        Redis::lPush($this->getListKey(), $value);
        // \Redis::BEFORE | \Redis::AFTER
        Redis::lInsert($this->getListKey(), 'BEFORE', '1', $value);

        $list = Redis::lRange($this->getListKey(), 0, -1);
        $this->assertContains($value, $list);
    }

    public function testBrpop(): void
    {
        $value = __METHOD__;
        Redis::del($this->getListKey());
        sgo(function () use ($value): void {
            usleep(20000);
            Redis::lPush($this->getListKey(), $value, $value, $value);
        });

        // 阻塞等待
        // 返回的key是真实插入的可以 带前缀
        [$key, $res] = Redis::brPop((array)$this->getListKey(), 1);

        $this->assertTrue(strpos($key, $this->getListKey()) !== false);
        $this->assertEquals($value, $res);
    }

    public function testLtrim(): void
    {
        Redis::rPush($this->getListKey(), 1);
        $res = Redis::lTrim($this->getListKey(), 1, -1);

        $this->assertTrue($res);
    }
}
