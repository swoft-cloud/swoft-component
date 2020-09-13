<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Bean\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Container;
use Swoft\Stdlib\Helper\Str;
use SwoftTest\Bean\Testing\Definition\PrototypeClass;
use SwoftTest\Bean\Testing\Definition\RequestClass;
use SwoftTest\Bean\Testing\Definition\SessionClass;
use SwoftTest\Bean\Testing\Definition\SingletonClass;
use SwoftTest\Bean\Testing\InjectBean;

class DefinitionTest extends TestCase
{
    public function testSingle(): void
    {
        /* @var SingletonClass $singletonClass */
        $singletonClass = BeanFactory::getBean('singleton');
        $this->assertInstanceOf(SingletonClass::class, $singletonClass);

        $this->beanCase($singletonClass);

        /* @var SingletonClass $singletonClass */
        $singletonClass = BeanFactory::getSingleton('singleton');
        $this->assertInstanceOf(SingletonClass::class, $singletonClass);

        $this->beanCase($singletonClass);
        $this->assertTrue(BeanFactory::hasBean('singleton'));

        /* @var SingletonClass $singletonClass */
        $singletonClass = BeanFactory::getBean('singleton-alias');
        $this->assertInstanceOf(SingletonClass::class, $singletonClass);

        $this->beanCase($singletonClass);

        /* @var SingletonClass $singletonClass */
        $singletonClass = BeanFactory::getSingleton('singleton-alias');
        $this->assertInstanceOf(SingletonClass::class, $singletonClass);

        $this->beanCase($singletonClass);
        $this->assertTrue(BeanFactory::hasBean('singleton-alias'));


        /* @var SingletonClass $singletonClass */
        $singletonClass = BeanFactory::getBean(SingletonClass::class);
        $this->assertInstanceOf(SingletonClass::class, $singletonClass);

        $this->beanCase($singletonClass);

        /* @var SingletonClass $singletonClass */
        $singletonClass = BeanFactory::getSingleton(SingletonClass::class);
        $this->assertInstanceOf(SingletonClass::class, $singletonClass);

        $this->assertTrue(BeanFactory::hasBean(SingletonClass::class));

        $this->beanCase($singletonClass);
    }

    public function testPrototype(): void
    {
        /* @var SingletonClass $prototypeClass */
        $prototypeClass = BeanFactory::getBean('prototype');
        $this->assertInstanceOf(PrototypeClass::class, $prototypeClass);

        $this->beanCase($prototypeClass);

        /* @var SingletonClass $singletonClass */
        $prototypeClass = BeanFactory::getBean('prototype-alias');
        $this->assertInstanceOf(PrototypeClass::class, $prototypeClass);

        $this->beanCase($prototypeClass);


        /* @var SingletonClass $singletonClass */
        $prototypeClass = BeanFactory::getBean(PrototypeClass::class);
        $this->assertInstanceOf(PrototypeClass::class, $prototypeClass);

        $this->beanCase($prototypeClass);
    }

    public function testRequest(): void
    {
        $id = Str::uniqID();

        /* @var RequestClass $requestClass */
        $requestClass = BeanFactory::getRequestBean('requestClass', $id);
        $this->beanCase($requestClass);

        /* @var RequestClass $requestClass */
        $requestClass = BeanFactory::getRequestBean('request-alias', $id);
        $this->beanCase($requestClass);

        /* @var RequestClass $requestClass */
        $requestClass = BeanFactory::getRequestBean(RequestClass::class, $id);
        $this->beanCase($requestClass);

        BeanFactory::destroyRequest($id);

        $this->assertEmpty(Container::getInstance()->getRequestPool());
    }

    public function testSession(): void
    {
        $id = Str::uniqID();

        /* @var SessionClass $sessionClass */
        $sessionClass = BeanFactory::getSessionBean('sessionClass', $id);
        $this->beanCase($sessionClass);

        /* @var SessionClass $sessionClass */
        $sessionClass = BeanFactory::getSessionBean('session-alias', $id);
        $this->beanCase($sessionClass);

        /* @var SessionClass $sessionClass */
        $sessionClass = BeanFactory::getSessionBean(SessionClass::class, $id);
        $this->beanCase($sessionClass);

        BeanFactory::destroySession($id);

        $this->assertEmpty(Container::getInstance()->getSessionPool());
    }

    /**
     * @param SingletonClass|PrototypeClass|RequestClass|SessionClass $singletonClass
     */
    private function beanCase($singletonClass): void
    {
        $this->assertEquals($singletonClass->getClassPrivate(), 'classPrivate');
        $this->assertEquals($singletonClass->getClassPublic(), 12);
        $this->assertEquals($singletonClass->getPrivateProp(), 'privateProp');
        $this->assertEquals($singletonClass->getPublicProp(), 12);
        $this->assertEquals($singletonClass->getSetProp(), 'setProp-setter');

        $injectBean = $singletonClass->getInjectBean();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletonClass->getInjectBeanAlias();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletonClass->getInjectBeanName();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletonClass->getInjectBeanClass();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletonClass->getDefinitionBean();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletonClass->getDefinitionBeanAlias();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletonClass->getDefinitionBeanClass();
        $this->assertInstanceOf(InjectBean::class, $injectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
    }
}
