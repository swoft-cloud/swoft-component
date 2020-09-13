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
use Swoft\Bean\BF;
use Swoft\Bean\InterfaceRegister;
use SwoftTest\Bean\Testing\Contract\PrimaryInterface;
use SwoftTest\Bean\Testing\Contract\TestInterface;
use SwoftTest\Bean\Testing\Definition\InterfaceBean;
use SwoftTest\Bean\Testing\Definition\InterfaceBeanDefinition;
use SwoftTest\Bean\Testing\Definition\InterfaceOne;
use SwoftTest\Bean\Testing\Definition\InterfaceTwo;
use SwoftTest\Bean\Testing\Definition\PrimaryInterfaceTwo;

/**
 * Class InterfaceTest
 *
 * @since 2.0
 */
class InterfaceTest extends TestCase
{
    public function testGetName(): void
    {
        /* @var InterfaceBean $testInterface */
        $testInterface = BF::getBean(InterfaceBean::class);

        $name = $testInterface->getName();
        $this->assertContains('Interface', $name);

        $name2 = $testInterface->getName2();
        $this->assertEquals('InterfaceTwo', $name2);

        $name3 = $testInterface->getName3();
        $this->assertEquals('InterfaceOne', $name3);

        $faceClass = TestInterface::class;

        $beanName = InterfaceRegister::getInterfaceInjectBean($faceClass);
        if ($beanName === 'interfaceOne') {
            $wantClass = InterfaceOne::class;
            $this->assertEquals($beanName, 'interfaceOne');
        } else {
            $wantClass = InterfaceTwo::class;
            $this->assertEquals($beanName, 'interfaceTwo');
        }

        $obj = BF::getBean($faceClass);
        $this->assertInstanceOf($wantClass, $obj);

        $obj = BF::getSingleton($faceClass);
        $this->assertInstanceOf($wantClass, $obj);
    }

    public function testGetPname(): void
    {
        /* @var InterfaceBean $testInterface */
        $testInterface = BeanFactory::getBean(InterfaceBean::class);

        $name = $testInterface->getPname();
        $this->assertContains('Primary', $name);

        $name3 = $testInterface->getPname2();
        $this->assertEquals('PrimaryInterfaceThree', $name3);

        $obj = BF::getBean(PrimaryInterface::class);
        $this->assertInstanceOf(PrimaryInterface::class, $obj);

        $beanName = InterfaceRegister::getInterfaceInjectBean(PrimaryInterface::class);
        $this->assertEquals($beanName, PrimaryInterfaceTwo::class);

        $obj = BF::getSingleton(PrimaryInterfaceTwo::class);
        $this->assertInstanceOf(PrimaryInterfaceTwo::class, $obj);
    }

    public function testGetPnameDefinition(): void
    {
        /* @var InterfaceBeanDefinition $testInterface */
        $testInterface = BF::getBean(InterfaceBeanDefinition::class);

        $name = $testInterface->getName();
        $this->assertEquals('PrimaryInterfaceTwo', $name);

        $obj = BF::getBean(PrimaryInterface::class);
        $this->assertInstanceOf(PrimaryInterface::class, $obj);

        $beanName = InterfaceRegister::getInterfaceInjectBean(PrimaryInterface::class);
        $this->assertEquals($beanName, PrimaryInterfaceTwo::class);

        $obj = BF::getSingleton(PrimaryInterfaceTwo::class);
        $this->assertInstanceOf(PrimaryInterfaceTwo::class, $obj);
    }
}
