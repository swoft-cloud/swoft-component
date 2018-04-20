<?php

namespace SwoftTest\Aop;

use Swoft\Aop\ProceedingJoinPoint;
use Swoft\Aop\Bean\Annotation\Around;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\None;
use Swoft\Aop\Bean\Annotation\PointAnnotation;


/**
 * Class NoneAnnotationAspect
 * @Aspect()
 * @PointAnnotation(
 *     include={
 *         None::class
 *     }
 * )
 */
class NoneAnnotationAspect
{

    /**
     * @Around()
     * @param ProceedingJoinPoint $joinPoint
     * @return string
     */
    public function Around(ProceedingJoinPoint $joinPoint): string
    {
        $result = $joinPoint->proceed();
        return implode(',', [
            'before',
            $result,
            'after'
        ]);
    }

}