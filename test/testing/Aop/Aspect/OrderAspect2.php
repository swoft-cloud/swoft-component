<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop\Aspect;

use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;

/**
 * Class OrderAspect2
 *
 * @since 2.0
 *
 * @Aspect()
 *
 * @PointBean(
 *     include={"testOrderAop"}
 * )
 */
class OrderAspect2
{
    /**
     * @var string
     */
    private $trace = '';

    /**
     * @Before()
     */
    public function before()
    {
        $this->trace .= 'before2-';

    }

    /**
     * @After()
     */
    public function after()
    {
        $this->trace .= 'after2-';

    }

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
        $result      = $joinPoint->getReturn();
        $this->trace .= sprintf('afterReturn2(%s)-', $result);


        $ret = $this->trace;
        $this->clear();

        return $ret;
    }

    /**
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     * @throws \Throwable
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->trace .= 'beforeAround2-';
        $result      = $proceedingJoinPoint->proceed();
        $this->trace .= $result . '-';
        $this->trace .= 'afterAround2-';

        return $result;
    }

    /**
     * @param \Throwable $throwable
     *
     * @AfterThrowing()
     *
     * @throws \Throwable
     */
    public function afterThrowing(\Throwable $throwable)
    {
        $this->trace .= \sprintf('afterThrowing2(%s)-', $throwable->getMessage());
    }

    /**
     * @return string
     */
    public function getTrace(): string
    {
        return $this->trace;
    }

    /**
     * Clear
     */
    public function clear()
    {
        $this->trace = '';
    }
}