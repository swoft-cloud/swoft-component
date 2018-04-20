<?php

namespace SwoftTest\Aop;

use Swoft\Aop\ProceedingJoinPoint;
use Swoft\Bean\Annotation\Around;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\None;
use Swoft\Bean\Annotation\PointAnnotation;


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