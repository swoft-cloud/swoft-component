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

use PHPUnit\Framework\TestCase;
use Swoft\Redis\Pool\Config\RedisPoolConfig;
use Swoft\Redis\Redis;

/**
 * AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var \Redis
     */
    public $redis;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->redis = bean(Redis::class);
        parent::__construct($name, $data, $dataName);
    }

    public function keysProvider()
    {
        $key = uniqid();
        $key2 = uniqid();

        $this->redis->set($key, uniqid());
        $this->redis->set($key2, uniqid());

        return [$key, $key2];
    }

    /**
     * @param string $key
     */
    protected function assertPrefix(string $key)
    {
        $result = $this->redis->getKeys(sprintf('*%s', $key));

        /* @var \Swoft\Redis\Pool\Config\RedisPoolConfig $redisConfig */
        $redisConfig = \bean(RedisPoolConfig::class);
        $prefix = $redisConfig->getPrefix();
        foreach ($result as $key) {
            $this->assertStringStartsWith($prefix, $key);
        }
    }
}
