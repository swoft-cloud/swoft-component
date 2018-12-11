<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Aop\Testing\Aop;

use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\PointBean;
use Swoft\Aop\JoinPoint;
use SwoftTest\Aop\Testing\Bean\NestBean;

/**
 * Class NestAspect
 * @Aspect
 * @PointBean(
 *     include={
 *         NestBean::class
 *     }
 * )
 */
class NestAspect
{
    /**
     * @AfterReturning
     * @param \Swoft\Aop\JoinPoint $joinPoint
     * @return string
     */
    public function afterReturn(JoinPoint $joinPoint): string
    {
        $result = $joinPoint->getReturn();
        return $result . '.afterReturn';
    }
}
