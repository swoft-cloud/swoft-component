<?php declare(strict_types=1);


namespace SwoftTest\Component\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use SwoftTest\Component\Testing\Config\DemoConfig;

/**
 * Class ConfigTest
 *
 * @since 2.0
 */
class ConfigTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testA()
    {
        $data = [
            'data'  => 'baseData',
            'array' => [
                'arr',
                'arr2',
            ],
            'other' => [
                'data'  => 'otherData',
                'array' => [
                    'arrOther',
                    'arr2Other',
                ]
            ]
        ];

        $result = config();
        $this->assertEquals($result, $data);

        /* @var DemoConfig $config */
        $config = BeanFactory::getBean(DemoConfig::class);
        $this->assertEquals('baseData', $config->getData());
        $this->assertEquals(['arr', 'arr2'], $config->getArray());
        $this->assertEquals('otherData', $config->getOtherData());
        $this->assertEquals(['arrOther', 'arr2Other'], $config->getOtherArray());

        /* @var DemoConfig $config */
        $config = BeanFactory::getBean('testDemoConfig');
        $this->assertEquals('baseData', $config->getData());
        $this->assertEquals(['arr', 'arr2'], $config->getArray());
        $this->assertEquals('otherData', $config->getOtherData());
        $this->assertEquals(['arrOther', 'arr2Other'], $config->getOtherArray());

        /* @var DemoConfig $config */
        $config = BeanFactory::getBean('testDemoConfigAlias');
        $this->assertEquals('baseData', $config->getData());
        $this->assertEquals(['arr', 'arr2'], $config->getArray());
        $this->assertEquals('otherData', $config->getOtherData());
        $this->assertEquals(['arrOther', 'arr2Other'], $config->getOtherArray());
    }
}