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

use Swoft\Aop\JoinPoint;
use Swoft\Aop\ProceedingJoinPoint;
use Swoft\Aop\Bean\Annotation\After;
use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\AfterThrowing;
use Swoft\Aop\Bean\Annotation\Around;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\Before;
use Swoft\Aop\Bean\Annotation\PointBean;

/**
 * the test of aspcet
 *
 * @Aspect
 * @PointBean(
 *     include={AopBean::class},
 * )
 */
class AllPointAspect
{
    /**
     * @var
     */
    private $test;

    /**
     * @Before
     */
    public function before()
    {
        $this->test .= ' before1 ';
    }

    /**
     * @After
     */
    public function after()
    {
        $this->test .= ' after1 ';
    }

    /**
     * @AfterReturning
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $result = $joinPoint->getReturn();
        return $result.' afterReturn1 ';
    }

    /**
     * @Around
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->test .= ' around-before1 ';
        $result = $proceedingJoinPoint->proceed();
        $this->test .= ' around-after1 ';
        return $result.$this->test;
    }

    /**
     * @AfterThrowing
     */
    public function afterThrowing()
    {
        echo "aop=1 afterThrowing !\n";
    }
}
