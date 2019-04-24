<?php declare(strict_types=1);


namespace SwoftTest\Component\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use SwoftTest\Component\Testing\Aop\Aspect\BeanAspect;
use SwoftTest\Component\Testing\Aop\Aspect\OrderAspect;
use SwoftTest\Component\Testing\Aop\Aspect\OrderAspect2;
use SwoftTest\Component\Testing\Aop\DemoAop;
use SwoftTest\Component\Testing\Aop\ExecutionAop;
use SwoftTest\Component\Testing\Aop\OrderAop;

/**
 * Class AopTest
 *
 * @since 2.0
 */
class AopTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testBean()
    {
        /* @var DemoAop $demoAop */
        $demoAop = BeanFactory::getBean(DemoAop::class);
        $result  = $demoAop->method();
        $this->assertEquals('beforeAround-before-doMethod-afterAround-after-afterReturn(doMethod)-', $result);

        /* @var DemoAop $demoAop */
        $demoAop = BeanFactory::getBean(DemoAop::class);
        $demoAop->ex();

        /* @var BeanAspect $spect */
        $spect = BeanFactory::getBean(BeanAspect::class);
        $this->assertEquals('beforeAround-before-after-afterThrowing(exception message)-', $spect->getTrace());
    }

    /**
     * @throws \Throwable
     */
    public function testOrder()
    {
        /* @var OrderAop $orderAop */
        $orderAop = BeanFactory::getBean(OrderAop::class);
        $result   = $orderAop->method();

        $orderStr = 'beforeAround1-before1-beforeAround2-before2-doMethod-afterAround2-after2-afterReturn2(doMethod)--afterAround1-after1-afterReturn1-';
        $this->assertEquals($orderStr, $result);

        /* @var OrderAop $orderAop */
        $orderAop = BeanFactory::getBean(OrderAop::class);
        $orderAop->ex();

        /* @var OrderAspect $spect */
        $spect = BeanFactory::getBean(OrderAspect::class);
        $this->assertEquals('beforeAround1-before1--afterAround1-after1-afterThrowing1(exception message)-',
            $spect->getTrace());

        /* @var OrderAspect2 $spect */
        $spect = BeanFactory::getBean(OrderAspect2::class);

        $this->assertEquals('beforeAround2-before2-after2-afterThrowing2(exception message)-', $spect->getTrace());
    }

    public function testExecution()
    {
        /* @var ExecutionAop $executionAop */
        $executionAop = BeanFactory::getBean(ExecutionAop::class);
        $result  = $executionAop->method();
        $this->assertEquals('beforeAround-before-doMethod-afterAround-after-afterReturn(doMethod)-', $result);

        /* @var DemoAop $executionAop */
        $executionAop = BeanFactory::getBean(ExecutionAop::class);
        $executionAop->ex();

        /* @var BeanAspect $spect */
        $spect = BeanFactory::getBean(BeanAspect::class);
        $this->assertEquals('beforeAround-before-after-afterThrowing(exception message)-', $spect->getTrace());
    }
}