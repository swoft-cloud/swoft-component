<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Aop;

use Swoft\Aop\ProceedingJoinPoint;
use Swoft\Aop\Bean\Annotation\Around;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\PointExecution;

/**
 * the aspect of test
 *
 * @Aspect
 * @PointExecution(
 *     include={
 *         "SwoftTest\Aop\RegBean::methodParams",
 *     }
 * )
 */
class ExeByNewParamsAspect
{
    /**
     * @Around
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return string
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $newArgs = [];
        $args = $proceedingJoinPoint->getArgs();
        foreach ($args as $arg) {
            $newArgs[] = $arg.'-new';
        }
        $tag = ' regAspect around before ';
        $result = $proceedingJoinPoint->proceed($newArgs);
        $tag .= ' regAspect around after ';
        return $result.$tag;
    }
}
