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

namespace SwoftTest\Sg\Cases;

use Swoft\Sg\Balancer\RandomBalancer;
use Swoft\Sg\Balancer\RoundRobinBalancer;

/**
 * Test
 */
class BalancerTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function random()
    {
        $list = [1, 2, 3];
        $balancer = new RandomBalancer();
        $value = $balancer->select($list);
        $this->assertTrue(\in_array($value, $list));
    }

    /**
     * @test
     */
    public function roundRobin()
    {
        $list = [1, 2, 3];
        $balancer = new RoundRobinBalancer();
        $value = $balancer->select($list);
        $this->assertEquals($list[0], $value);
        $value = $balancer->select($list);
        $this->assertEquals($list[1], $value);
        $value = $balancer->select($list);
        $this->assertEquals($list[2], $value);
        $value = $balancer->select($list);
        $this->assertEquals($list[0], $value);
    }
}
