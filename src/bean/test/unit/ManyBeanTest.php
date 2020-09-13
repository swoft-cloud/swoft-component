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
use SwoftTest\Bean\Testing\Definition\CommaNameClass;
use SwoftTest\Bean\Testing\Definition\ManyInstance;

/**
 * Class ManyBeanTest
 *
 * @since 2.0
 */
class ManyBeanTest extends TestCase
{
    public function testMany(): void
    {
        $beans = Container::getInstance()->gets(ManyInstance::class);

        $this->assertCount(3, $beans);

        foreach ($beans as $bean) {
            $this->assertInstanceOf(ManyInstance::class, $bean);
        }

        $beans = BeanFactory::getBeans(ManyInstance::class);
        $this->assertCount(3, $beans);

        foreach ($beans as $bean) {
            $this->assertInstanceOf(ManyInstance::class, $bean);
        }

        /* @var CommaNameClass $comma */
        $comma = BeanFactory::getBean(CommaNameClass::class);

        $two = $comma->getManyInstance2();

        $this->assertInstanceOf(ManyInstance::class, $two);
    }
}
