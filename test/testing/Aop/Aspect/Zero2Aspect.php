<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop\Aspect;


use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\PointExecution;
use Swoft\Aop\Point\JoinPoint;
use Throwable;

/**
 * Class Zero2Aspect
 *
 * @since 2.0
 *
 *
 * @Aspect()
 * @PointExecution(
 *     include={
 *         "SwoftTest\Component\Testing\Aop\ZeroAop::afterZero"
 *     }
 * ))
 */
class Zero2Aspect
{
    /**
     * @AfterReturning()
     *
     * @param JoinPoint $joinPoint
     *
     * @return mixed
     * @throws Throwable
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        return 0;
    }
}