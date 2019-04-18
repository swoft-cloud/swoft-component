<?php declare(strict_types=1);


namespace SwoftTest\Bean\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use SwoftTest\Bean\Testing\Definition\PrototypeClass;
use SwoftTest\Bean\Testing\Definition\SingletonClass;
use SwoftTest\Bean\Testing\InjectBean;

class DefinitionTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testSingle()
    {
        /* @var SingletonClass $singletoClass */
        $singletoClass = BeanFactory::getBean('singleton');
        $this->assertTrue($singletoClass instanceof SingletonClass);

        /* @var SingletonClass $singletoClass */
        $singletoClass = BeanFactory::getSingleton('singleton');
        $this->assertTrue($singletoClass instanceof SingletonClass);

        /* @var SingletonClass $singletoClass */
        $singletoClass = BeanFactory::getBean('singleton-alias');
        $this->assertTrue($singletoClass instanceof SingletonClass);

        /* @var SingletonClass $singletoClass */
        $singletoClass = BeanFactory::getSingleton('singleton-alias');
        $this->assertTrue($singletoClass instanceof SingletonClass);

        /* @var SingletonClass $singletoClass */
        $singletoClass = BeanFactory::getBean(SingletonClass::class);
        $this->assertTrue($singletoClass instanceof SingletonClass);

        /* @var SingletonClass $singletoClass */
        $singletoClass = BeanFactory::getSingleton(SingletonClass::class);
        $this->assertTrue($singletoClass instanceof SingletonClass);

        $this->assertEquals($singletoClass->getClassPrivate(), 'classPrivate');
        $this->assertEquals($singletoClass->getClassPublic(), 12);
        $this->assertEquals($singletoClass->getPrivateProp(), 'privateProp');
        $this->assertEquals($singletoClass->getPublicProp(), 12);
        $this->assertEquals($singletoClass->getSetProp(), 'setProp-setter');

        $injectBean = $singletoClass->getInjectBean();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletoClass->getInjectBeanAlias();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletoClass->getInjectBeanName();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletoClass->getInjectBeanClass();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletoClass->getDefinitionBean();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletoClass->getDefinitionBeanAlias();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');

        $injectBean = $singletoClass->getDefinitionBeanClass();
        $this->assertTrue($injectBean instanceof InjectBean);
        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testPrototype()
    {
        /* @var SingletonClass $prototypeClass */
        $prototypeClass = BeanFactory::getBean('prototype');
        $this->assertTrue($prototypeClass instanceof PrototypeClass);

        /* @var SingletonClass $singletoClass */
        $prototypeClass = BeanFactory::getBean('prototype-alias');
        $this->assertTrue($prototypeClass instanceof PrototypeClass);

        /* @var SingletonClass $singletoClass */
        $prototypeClass = BeanFactory::getBean(PrototypeClass::class);
        $this->assertTrue($prototypeClass instanceof PrototypeClass);

//        $this->assertEquals($prototypeClass->getClassPrivate(), 'classPrivate');
//        $this->assertEquals($prototypeClass->getClassPublic(), 12);
//        $this->assertEquals($prototypeClass->getPrivateProp(), 'privateProp');
//        $this->assertEquals($prototypeClass->getPublicProp(), 12);
//        $this->assertEquals($prototypeClass->getSetProp(), 'setProp-setter');
//
//        $injectBean = $prototypeClass->getInjectBean();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
//
//        $injectBean = $prototypeClass->getInjectBeanAlias();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
//
//        $injectBean = $prototypeClass->getInjectBeanName();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
//
//        $injectBean = $prototypeClass->getInjectBeanClass();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
//
//        $injectBean = $prototypeClass->getDefinitionBean();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
//
//        $injectBean = $prototypeClass->getDefinitionBeanAlias();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
//
//        $injectBean = $prototypeClass->getDefinitionBeanClass();
//        $this->assertTrue($injectBean instanceof InjectBean);
//        $this->assertEquals($injectBean->getData(), 'InjectBeanData-InjectChildBeanData');
    }
}