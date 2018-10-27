<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest;

use Swoft\App;
use Swoft\Bean\Resource\ServerAnnotationResource;
use Swoft\Proxy\Proxy;
use SwoftTest\Bean\ProxyTest;
use SwoftTest\Bean\TestHandler;
use SwoftTest\Testing\Bean\Config;

/**
 * Class BeanTest
 *
 * @package Swoft\Test\Cases
 */
class BeanTest extends AbstractTestCase
{
    public function testProxy()
    {
        $object = new ProxyTest(1, 2);
        $handler = new TestHandler($object);

        /* @var ProxyTest $proxy */
        $proxy = Proxy::newProxyInstance(ProxyTest::class, $handler);
        $this->assertEquals('p11beforeafter', $proxy->publicParams('p1', 'p2'));
        $this->assertEquals('p1p20beforeafter', $proxy->publicFun1('p1', 'p2'));
        $this->assertEquals('p1p23beforeafter', $proxy->publicFun1('p1', 'p2', [1, 2, 3]));
        $this->assertEquals('p1p21beforeafter', $proxy->publicFun2('p1', 'p2'));
        $this->assertEquals('p1p21beforeafter', $proxy->publicFun3('p1', 'p2'));
        $this->assertEquals('p1p25beforeafter', $proxy->publicFun3('p1', 'p2', 5));
        $this->assertEquals('p1p2beforeafter', $proxy->publicFun1Base('p1', 'p2'));
        $this->assertEquals('p1p21.6beforeafter', $proxy->publicFun2Base('p1', 'p2', 1.6));
        $this->assertEquals('p1p2beforeafter', $proxy->publicFun1Trait('p1', 'p2'));
        $this->assertEquals('p1p2beforeafter', $proxy->publicFun2Trait('p1', 'p2'));
        $this->assertEquals('p1p2beforeafter', $proxy->publicFun3Trait('p1', 'p2'));
    }

    public function testCustomComponentNamespaces()
    {
        $config = App::getProperties()->toArray();
        $this->assertArrayHasKey('components', $config);
        $resource = new ServerAnnotationResource($config);
        $resource->registerNamespace();

        $namespace = $resource->getComponentNamespaces();
        $this->assertNotFalse(array_search('SwoftTest', $namespace));
    }

    public function testCustomComponentSupportAlias()
    {
        $config = bean(Config::class);
        $this->assertEquals('test', $config->getName());

        $config = bean(\SwoftTest\Testing\Bean2\Config::class);
        $this->assertEquals('test', $config->getName());
    }
}
