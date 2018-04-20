<?php

namespace Swoft\Aop;

/**
 * AopInterface
 */
interface AopInterface
{
    /**
     * register aop
     *
     * @param array $aspects
     */
    public function register(array $aspects);
}
