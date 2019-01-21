<?php

namespace Swoft\Sg\Balancer;

use Swoft\Bean\Annotation\Bean;

/**
 * 随机选取负载
 *
 * @Bean()
 */
class RandomBalancer implements BalancerInterface
{
    public function select(array $serviceList, ...$params)
    {
        $randIndex = array_rand($serviceList);
        return $serviceList[$randIndex];
    }
}
