<?php

namespace SwoftTest\Redis;

use PHPUnit\Framework\TestCase;
use Swoft\Redis\Redis;

/**
 * AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var \Swoft\Redis\Redis
     */
    protected $redis;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->redis = bean(Redis::class);
        parent::__construct($name, $data, $dataName);
    }


    /**
     * Tear down
     */
    protected function tearDown()
    {
        parent::tearDown();
        swoole_timer_after(1 * 1000, function () {
            swoole_event_exit();
        });
    }

    public function keysProvider()
    {
        $key  = uniqid();
        $key2 = uniqid();

        $this->redis->set($key, uniqid());
        $this->redis->set($key2, uniqid());

        return [
            [[$key, $key2]],
        ];
    }

    protected function setCoName($name): String
    {
        $name = "{$name}-co";

        return $name;
    }
}