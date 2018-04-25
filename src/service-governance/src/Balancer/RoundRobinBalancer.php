<?php

namespace Swoft\Sg\Balancer;

use Swoft\Bean\Annotation\Bean;

/**
 * Polling load
 * @Bean()
 */
class RoundRobinBalancer implements BalancerInterface
{
    /** @var int */
    private $lastIndex = 0;

    /**
     * @param array $serviceList
     * @param array ...$params
     * @return mixed
     */
    public function select(array $serviceList, ...$params)
    {
        $currentIndex = $this->lastIndex;
        $value = $serviceList[$currentIndex];

        if ($currentIndex + 1 > \count($serviceList) - 1) {
            $this->lastIndex = 0;
        } else {
            $this->lastIndex++;
        }

        return $value;
    }
}
