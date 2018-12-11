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

use Swoft\Aop\Bean\Annotation\Around;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\None;
use Swoft\Aop\Bean\Annotation\PointAnnotation;
use Swoft\Aop\ProceedingJoinPoint;

/**
 * Class NoneAnnotationAspect
 * @Aspect
 * @PointAnnotation(
 *     include={
 *         None::class
 *     }
 * )
 */
class NoneAnnotationAspect
{
    /**
     * @Around
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
