<?php

namespace SwoftTest\Redis;

use Swoft\App;
use Swoft\Redis\Redis;

class RedisCommandTest extends AbstractTestCase
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
     */
    public function existsCo()
    {
        $key    = 'exists-command';
        $value  = 'set-value';
        $key    = $this->setCoName($key);
        $value  = $this->setCoName($value);
        go(function() use ($key, $value) {
            $result = $this->redis->set($key, $value);
            $this->assertTrue($result);
            $result = $this->redis->has($key);
            $this->assertTrue($result);
        });
    }
    
}
