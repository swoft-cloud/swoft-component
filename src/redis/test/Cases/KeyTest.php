<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Redis\Cases;

use Swoft\Redis\Pool\Config\RedisPoolConfig;

/**
 * KeyTest
 */
class KeyTest extends AbstractTestCase
{
    /**
     * @param array $keys
     */
    public function testDelete()
    {
        $keys = $this->keysProvider();
        $result = $this->redis->deleteMultiple($keys);
        $this->assertTrue($result);

        foreach ($keys as $key) {
            $result = $this->redis->get($key, false);
            $this->assertFalse($result);
        }
    }

    public function testKeys()
    {
        $result = $this->redis->getKeys('*');

        $redisConfig = \bean(RedisPoolConfig::class);
        $prefix = $redisConfig->getPrefix();
        foreach ($result as $key) {
            $this->assertStringStartsWith($prefix, $key);
        }
    }

    /**
     * @param array $keys
     */
    public function testHas()
    {
        $keys = $this->keysProvider();

        foreach ($keys as $key) {
            $result = $this->redis->has($key);
            $this->assertTrue($result);
        }

        $result = $this->redis->deleteMultiple($keys);
        $this->assertTrue($result);

        foreach ($keys as $key) {
            $result = $this->redis->has($key);
            $this->assertFalse($result);
        }
    }

    public function testRandomKey()
    {
        $result = $this->redis->randomKey();
        $key = str_replace('swoft-test-redis_', '', $result);
        $this->assertTrue($this->redis->exists($key) == true);
    }
}
