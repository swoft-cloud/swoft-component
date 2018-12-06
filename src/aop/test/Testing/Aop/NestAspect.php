<?php

namespace SwoftTest\Aop\Testing\Aop;

use Swoft\Aop\JoinPoint;
use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\PointBean;
use SwoftTest\Aop\Testing\Bean\NestBean;

/**
 * Class NestAspect
 * @Aspect()
 * @PointBean(
 *     include={
 *          NestBean::class
 *     }
 * )
 */
class NestAspect
{

    /**
     * @AfterReturning()
     * @param \Swoft\Aop\JoinPoint $joinPoint
     * @return string
     */
    public function afterReturn(JoinPoint $joinPoint): string
    {
        $result = $joinPoint->getReturn();
        return $result . '.afterReturn';
    }

}