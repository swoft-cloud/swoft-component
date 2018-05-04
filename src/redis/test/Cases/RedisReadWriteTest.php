<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Redis;

use Swoft\Redis\Redis;

class RedisReadWriteTest extends AbstractTestCase
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
     * @covers Redis::has()
     * @covers Redis::get()
     * @covers Redis::set()
     */
    public function testCo()
    {
        go(function () {
            $this->redis->set('a', '1', 10);
            if ($this->redis->has('a')) {
                $a = $this->redis->get('a');
                var_dump($a);
                $this->assertEquals($a, 1);
            }
        });
        $this->assertNotNull(1);
    }

    /**
     * @test
     * @covers Redis::has()
     * @covers Redis::get()
     * @covers Redis::set()
     */
    public function testNormal()
    {
        $this->redis->set('a', '1', 10);
        if ($this->redis->has('a')) {
            $a = $this->redis->get('a');
            $this->assertEquals($a, '1');
        }
    }
}
