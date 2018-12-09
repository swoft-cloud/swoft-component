<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Cases;

use Swoft\App;
use Swoft\Bean\Resource\ServerAnnotationResource;
use SwoftTest\Testing\Bean\ProxyTest;
use SwoftTest\Testing\Bean\TestHandler;
use SwoftTest\Testing\Bean\Config;

/**
 * Class BeanTest
 *
 * @package Swoft\Test\Cases
 */
class BeanTest extends AbstractTestCase
{
    public function testCustomComponentNamespaces()
    {
        $config = App::getProperties()->toArray();
        $this->assertArrayHasKey('components', $config);
        $resource = new ServerAnnotationResource($config);
        $resource->registerNamespace();
        $resource->registerCustomNamespace();

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
