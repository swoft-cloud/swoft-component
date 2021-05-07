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
use function count;

/**
 * Class MultiTest
 *
 * @since 2.0
 */
class MultiTest extends RedisTestCase
{
    public function testPipeline(): void
    {
        $count = 100;

        for ($i = 0; $i < $count; $i++) {
            $result = Redis::pipeline(function (\Redis $redis) use ($count): void {
                for ($i = 0; $i < $count; $i++) {
                    $redis->set("key:$i", $i);
                }
            });

            $this->assertEquals(count($result), $count);

            foreach ($result as $index => $value) {
                $this->assertTrue($value);
            }
        }
    }

    public function testTransaction(): void
    {
        $count  = 2;
        $result = Redis::transaction(function (\Redis $redis) use ($count): void {
            for ($i = 0; $i < $count; $i++) {
                $key = "key:$i";
                $redis->set($key, $i);
                $redis->get($key);
            }
        });

        /**
         * array(4) {
         * [0]=>
         * bool(true)
         * [1]=>
         * int(0)
         * [2]=>
         * bool(true)
         * [3]=>
         * int(1)
         * }
         */

        $this->assertEquals(count($result), $count * 2);

        foreach ($result as $index => $value) {
            if ($index % 2 == 0) {
                $this->assertTrue($value);
            } else {
                $this->assertGreaterThan(0, $index + 1);
            }
        }
    }
}
