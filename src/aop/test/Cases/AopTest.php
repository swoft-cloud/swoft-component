<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Aop\Cases;

use Swoft\App;
use SwoftTest\Aop\Testing\Aop\AllPointAspectWithoutRound1;
use SwoftTest\Aop\Testing\Aop\AllPointAspectWithoutRound2;
use SwoftTest\Aop\Testing\Bean\AnnotationAop;
use SwoftTest\Aop\Testing\Bean\AopBean;
use SwoftTest\Aop\Testing\Bean\AopBean2;
use SwoftTest\Aop\Testing\Bean\NestBean;
use SwoftTest\Aop\Testing\Bean\RegBean;

/**
 * @uses      AopTest
 * @version   2017年12月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AopTest extends AbstractTestCase
{
    public function testAllAdvice()
    {
        /* @var AopBean $aopBean */
        $aopBean = App::getBean(AopBean::class);
        $result = $aopBean->doAop();
        $this->assertSame('do aop around-before2  before2  around-after2  afterReturn2  around-before1  before1  around-after1  afterReturn1 ', $result);
    }

    /**
     * 验证问题:当切面不包含Around型通知时，不支持多层切面
     *
     * @author Jiankang maijiankang@foxmail.com
     */
    public function testNestWithourRound()
    {
        $aopBean = App::getBean(AopBean2::class);

        ob_start();
        $aopBean->doAop();
        $echoContent = ob_get_contents();
        ob_end_clean();

        $this->assertSame(' before1withoutaround  before2withoutaround do aop after2withoutaround  afterReturn2withoutaround  after1withoutaround  afterReturn1withoutaround ', $echoContent);
    }

    public function testAnnotationAop()
    {
        $annotationBean = App::getBean(AnnotationAop::class);
        $result = $annotationBean->cacheable();
        $this->assertSame('cacheable around before  around after ', $result);

        $result = $annotationBean->cachePut();
        $this->assertSame('cachePut around before  around after ', $result);
    }

    public function testCustomAnnotationAop()
    {
        /* @var AnnotationAop $annotationBean */
        $annotationBean = App::getBean(AnnotationAop::class);
        $result = $annotationBean->demoAnnotation();
        $this->assertSame('demo hello around before  around after ', $result);
    }

    public function testRegAop()
    {
        $annotationBean = App::getBean(RegBean::class);
        $result = $annotationBean->regMethod();
        $this->assertSame('regMethod RegAspect around before  RegAspect around after ', $result);

        $result = $annotationBean->regMethod2();
        $this->assertSame('regMethod2 RegAspect around before  RegAspect around after ', $result);
    }

    public function testNewAopParams()
    {
        /* @var RegBean $annotationBean */
        $annotationBean = App::getBean(RegBean::class);
        $result = $annotationBean->methodParams('a', 'b');
        $this->assertSame('methodParams-a-new-b-new regAspect around before  regAspect around after ', $result);
    }

    /**
     * 测试AfterThrowing切面 能否从JoinPoint获取异常
     *
     * @author Jiankang maijiankang@foxmail.com
     */
    public function testThrowableInjectByJoinPoint()
    {
        $aopBean = App::getBean(AopBean2::class);
        AllPointAspectWithoutRound1::$catch = null;
        $exception = new \LogicException('Bomb!');
        ob_start();
        $aopBean->throwSth($exception);
        ob_end_clean();

        $this->assertSame($exception, AllPointAspectWithoutRound1::$catch);
    }

    /**
     * 测试AfterThrowing切面 能否直接注入异常
     *
     * @author Jiankang maijiankang@foxmail.com
     */
    public function testThrowableInject()
    {
        /* @var \SwoftTest\Aop\AopBean2 $aopBean */
        $aopBean = App::getBean(AopBean2::class);
        AllPointAspectWithoutRound2::$catch = null;
        $exception = new \LogicException('Bomb!');
        ob_start();
        $aopBean->throwSth($exception);
        ob_end_clean();
        $this->assertSame($exception, AllPointAspectWithoutRound2::$catch);
    }

    /**
     * @test
     */
    public function nestMethodBean()
    {
        /** @var NestBean $bean */
        $bean = \bean(NestBean::class);
        $this->assertSame('method2.afterReturn.method1.afterReturn', $bean->method1());
    }
}
