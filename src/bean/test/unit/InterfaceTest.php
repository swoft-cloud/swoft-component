<?php declare(strict_types=1);


namespace SwoftTest\Bean\Unit;


use PHPUnit\Framework\TestCase;
use ReflectionException;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use SwoftTest\Bean\Testing\Definition\InterfaceBean;
use SwoftTest\Bean\Testing\Definition\InterfaceBeanDefinition;

/**
 * Class InterfaceTest
 *
 * @since 2.0
 */
class InterfaceTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testGetName(): void
    {
        /* @var InterfaceBean $testInterface */
        $testInterface = BeanFactory::getBean(InterfaceBean::class);

        $name = $testInterface->getName();
        $this->assertContains('Interface', $name);

        $name2 = $testInterface->getName2();
        $this->assertEquals('InterfaceTwo', $name2);

        $name3 = $testInterface->getName3();
        $this->assertEquals('InterfaceOne', $name3);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testGetPname(): void
    {
        /* @var InterfaceBean $testInterface */
        $testInterface = BeanFactory::getBean(InterfaceBean::class);

        $name = $testInterface->getPname();
        $this->assertContains('Primary', $name);

        $name3 = $testInterface->getPname2();
        $this->assertEquals('PrimaryInterfaceThree', $name3);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testGetPnameDefinition(): void
    {
        /* @var InterfaceBeanDefinition $testInterface */
        $testInterface = BeanFactory::getBean(InterfaceBeanDefinition::class);

        $name = $testInterface->getName();
        $this->assertEquals('PrimaryInterfaceTwo', $name);
    }
}