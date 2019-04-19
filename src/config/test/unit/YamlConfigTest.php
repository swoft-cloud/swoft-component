<?php declare(strict_types=1);


namespace SwoftTest\Config\Unit;

use Swoft\Config\Config;

/**
 * Class YamlConfigTest
 *
 * @since 2.0
 */
class YamlConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Set up
     */
    public function setUp()
    {
        $config = new Config();
        $config->setPath(__DIR__ . '/../config-yaml');
        $config->setType(Config::TYPE_YAML);
        $config->init();;

        $this->config = $config;
    }

    public function testData()
    {
        $data = $this->config->getIterator()->getArrayCopy();

        $this->assertEquals($data, $this->getConfigData());
    }

    public function testGet()
    {
        $value2 = $this->config->get('key2');
        $this->assertEquals($value2, 'value2');

        $value2 = $this->config->get('key.key2');
        $this->assertEquals($value2, 'value2');

        $dbUser = $this->config->get('db.user');

        $this->assertEquals($dbUser, 'db');

        $defaultUser = $this->config->get('db.user.key', 'defaultUser');

        $this->assertEquals($defaultUser, 'defaultUser');
    }

    public function testOffsetGet()
    {
        $value2 = $this->config->offsetGet('key2');
        $this->assertEquals($value2, 'value2');

        $value2 = $this->config->offsetGet('key.key2');
        $this->assertEquals($value2, 'value2');

        $dbUser = $this->config->offsetGet('db.user');

        $this->assertEquals($dbUser, 'db');
    }

    public function testOffsetExists()
    {
        $result = $this->config->offsetExists('db.user.key');
        $this->assertFalse($result);

        $result = $this->config->offsetExists('db.user');
        $this->assertTrue($result);
    }

    /**
     * @expectedException Swoft\Config\Exception\ConfigException
     * @throws \Swoft\Config\Exception\ConfigException
     */
    public function testForget()
    {
        $this->config->forget('');
    }

    /**
     * @expectedException Swoft\Config\Exception\ConfigException
     * @throws \Swoft\Config\Exception\ConfigException
     */
    public function testOffsetUnset()
    {
        $this->config->offsetUnset('');
    }

    /**
     * @expectedException Swoft\Config\Exception\ConfigException
     * @throws \Swoft\Config\Exception\ConfigException
     */
    public function testOffsetSett()
    {
        $this->config->offsetSet('key', 'value');
    }
}