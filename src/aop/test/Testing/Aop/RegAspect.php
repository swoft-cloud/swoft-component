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
use Swoft\Aop\Bean\Annotation\PointExecution;
use Swoft\Aop\ProceedingJoinPoint;

/**
 * @Aspect
 * @PointExecution(
 *     include={
 *         "SwoftTest\Aop\Testing\Bean\RegBean::reg.*",
 *     }
 * )
 *
 * @uses      RegAspect
 * @version   2017年12月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RegAspect
{
    /**
     * @Around
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $tag = ' RegAspect around before ';
        $result = $proceedingJoinPoint->proceed();
        $tag .= ' RegAspect around after ';
        return $result.$tag;
    }
}
