<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop\Aspect;

use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\PointExecution;
use Swoft\Aop\Point\JoinPoint;

/**
 * Class RegAspect
 *
 * @since 2.0
 *
 * @Aspect()
 * @PointExecution(
 *     include={
 *         "SwoftTest\\Component\\Testing\\Aop\\Reg.*::.*"
 *     }
 * )
 */
class RegAspect
{
    /**
     * @AfterReturning()
     *
     * @param JoinPoint $joinPoint
     *
     * @throws \Throwable
     * @return mixed
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        return 'RegAspect='.$joinPoint->getReturn();
    }
}