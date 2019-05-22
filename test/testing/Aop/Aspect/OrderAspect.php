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
 * Class OrderAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointBean(
 *     include={"testOrderAop"}
 * )
 */
class OrderAspect
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
        $this->trace .= 'before1-';
    }

    /**
     * @After()
     */
    public function after()
    {
        $this->trace .= 'after1-';
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
        $joinPoint->getReturn();
        $this->trace .= 'afterReturn1-';


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
        $this->trace .= 'beforeAround1-';

        $result      = $proceedingJoinPoint->proceed();
        $this->trace .= $result . '-';
        $this->trace .= 'afterAround1-';


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
        $this->trace .= \sprintf('afterThrowing1(%s)-', $throwable->getMessage());

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