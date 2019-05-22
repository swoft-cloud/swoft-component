<?php declare(strict_types=1);


namespace SwoftTest\I18n\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\I18n\I18n;

/**
 * Class I18nTest
 *
 * @since 2.0
 */
class I18nTest extends TestCase
{
    /**
     * @var I18n
     */
    private $i18n;

    /**
     * @throws \ReflectionException
     * @throws ContainerException
     */
    public function setUp()
    {
        $this->i18n = BeanFactory::getBean('i18n');
    }

    /**
     * Translate
     */
    public function testTranslate()
    {
        $title = $this->i18n->translate('title', ['name' => 'Swoft']);
        $this->assertEquals($title, 'Hello Swoft');

        $title = $this->i18n->translate('title', ['name' => 'Swoft'], 'zh-cn');
        $this->assertEquals($title, '你好 Swoft');

        $title = $this->i18n->translate('msg.body', ['name' => 'Swoft', 'base' => 'Swoole']);
        $this->assertEquals($title, 'Swoft framework，base on Swoole');

        $title = $this->i18n->translate('msg.body', ['name' => 'Swoft', 'base' => 'Swoole'], 'zh-cn');
        $this->assertEquals($title, 'Swoft框架协程框架，基于Swoole');
    }
}