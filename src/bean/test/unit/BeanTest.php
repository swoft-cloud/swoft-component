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
use SwoftTest\Bean\Testing\Definition\TypeBean;
use SwoftTest\Bean\Testing\InjectBean;

/**
 * Class BeanTest
 *
 * @since 2.0
 */
class BeanTest extends TestCase
{
    public function testType(): void
    {
        /* @var TypeBean $typeBean */
        $typeBean = BeanFactory::getBean('testTypeBean');

        $this->assertEquals($typeBean->getStringVar(), '1');
        $this->assertEquals($typeBean->getIntVar(), 1);
        $this->assertEquals($typeBean->getIntegerVar(), 2);
        $this->assertEquals($typeBean->getFloatVar(), 1.1);
        $this->assertEquals($typeBean->getDoubleVar(), 1.2);

        /* @var TypeBean $typeBean */
        $typeBean = BeanFactory::getBean(TypeBean::class);

        $this->assertEquals($typeBean->getStringVar(), '1');
        $this->assertEquals($typeBean->getIntVar(), 1);
        $this->assertEquals($typeBean->getIntegerVar(), 2);
        $this->assertEquals($typeBean->getFloatVar(), 1.1);
        $this->assertEquals($typeBean->getDoubleVar(), 1.2);
    }

    public function testCreate(): void
    {
        $config = [
            'class' => InjectBean::class
        ];
        $bean   = BF::createBean('createInejctBean', $config);
        $this->assertInstanceOf(InjectBean::class, $bean);

        $bean2 = BF::getBean('createInejctBean');
        $this->assertInstanceOf(InjectBean::class, $bean2);
    }
}
