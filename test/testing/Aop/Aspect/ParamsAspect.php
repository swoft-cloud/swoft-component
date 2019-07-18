<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop\Aspect;

use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;
use SwoftTest\Component\Testing\Aop\ParamsAop;
use Throwable;

/**
 * Class ParamsAspect
 *
 * @since 2.0
 *
 * @Aspect()
 * @PointBean(
 *     include={ParamsAop::class}
 * )
 */
class ParamsAspect
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
        $result = $joinPoint->getReturn();
        $result .= json_encode($joinPoint->getArgsMap());
        $result .= $joinPoint->getClassName();

        return $result;
    }
}