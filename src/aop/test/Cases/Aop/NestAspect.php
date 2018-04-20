<?php

namespace SwoftTest\Aop;

use Swoft\Aop\JoinPoint;
use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\PointBean;


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