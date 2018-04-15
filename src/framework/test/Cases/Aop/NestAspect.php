<?php

namespace SwoftTest\Aop;

use Swoft\Aop\JoinPoint;
use Swoft\Bean\Annotation\AfterReturning;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\PointBean;


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