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
use SwoftTest\Pool\ConsulEnvConfig;
use SwoftTest\Pool\ConsulPptConfig;
use SwoftTest\Pool\DemoPool;
use SwoftTest\Pool\DemoPoolConfig;
use SwoftTest\Pool\EnvAndPptFromPptPoolConfig;
use SwoftTest\Pool\EnvAndPptPoolConfig;
use SwoftTest\Pool\EnvPoolConfig;
use SwoftTest\Pool\PartEnvPoolConfig;
use SwoftTest\Pool\PartPoolConfig;
use SwoftTest\Pool\PropertyPoolConfig;

/**
 * Class PoolTest
 *
 * @package Swoft\Test\Cases
 */
class PoolTest extends AbstractTestCase
{
    public function testPoolConfigByProperties()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(PropertyPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'test');
        $this->assertEquals($pConfig->getProvider(), 'p');
        $this->assertEquals($pConfig->getTimeout(), 1);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:6379',
            '127.0.0.1:6378',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'b');
        $this->assertEquals($pConfig->getMaxActive(), 1);
        $this->assertEquals($pConfig->isUseProvider(), true);
        $this->assertEquals($pConfig->getMaxWait(), 1);
    }

    public function testPartConfigByProperties()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(PartPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'test');
        $this->assertEquals($pConfig->getProvider(), 'consul');
        $this->assertEquals($pConfig->getTimeout(), 3);
        $this->assertEquals($pConfig->getUri(), []);
        $this->assertEquals($pConfig->getBalancer(), 'b');
        $this->assertEquals($pConfig->getMaxActive(), 1);
        $this->assertEquals($pConfig->isUseProvider(), false);
        $this->assertEquals($pConfig->getMaxWait(), 1);
    }

    public function testPoolConfigEnv()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(EnvPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'test');
        $this->assertEquals($pConfig->getProvider(), 'c1');
        $this->assertEquals($pConfig->getTimeout(), 2);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:6378',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'r1');
        $this->assertEquals($pConfig->getMaxActive(), 2);
        $this->assertEquals($pConfig->isUseProvider(), true);
        $this->assertEquals($pConfig->getMaxWait(), 2);
    }

    public function testPoolConfigEnvPart()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(PartEnvPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'test');
        $this->assertEquals($pConfig->getProvider(), 'c1');
        $this->assertEquals($pConfig->getTimeout(), 2);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:6378',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'random');
        $this->assertEquals($pConfig->getMaxActive(), 2);
        $this->assertEquals($pConfig->isUseProvider(), false);
        $this->assertEquals($pConfig->getMaxWait(), 20);
    }

    public function testPoolConfigEnvAndEnv()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(EnvAndPptPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'test');
        $this->assertEquals($pConfig->getProvider(), 'c1');
        $this->assertEquals($pConfig->getTimeout(), 2);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:6378',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'r1');
        $this->assertEquals($pConfig->getMaxActive(), 2);
        $this->assertEquals($pConfig->isUseProvider(), true);
        $this->assertEquals($pConfig->getMaxWait(), 2);
    }

    public function testPoolConfigEnvAndConfig()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(EnvAndPptFromPptPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'test2');
        $this->assertEquals($pConfig->getProvider(), 'p2');
        $this->assertEquals($pConfig->getTimeout(), 2);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:6379',
            '127.0.0.1:6378',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'b2');
        $this->assertEquals($pConfig->getMaxActive(), 2);
        $this->assertEquals($pConfig->isUseProvider(), true);
        $this->assertEquals($pConfig->getMaxWait(), 2);
    }

    public function testConsulPpt()
    {
        /* @var ConsulPptConfig $pConfig */
        $pConfig = App::getBean(ConsulPptConfig::class);
        $this->assertEquals('http://127.0.0.1:81', $pConfig->getAddress());
        $this->assertEquals(1, $pConfig->getTimeout());
        $this->assertEquals(1, $pConfig->getInterval());
        $this->assertEquals(['1'], $pConfig->getTags());
    }

    public function testConsulEnv()
    {
        /* @var ConsulPptConfig $pConfig */
        $pConfig = App::getBean(ConsulEnvConfig::class);
        $this->assertEquals('http://127.0.0.1:82', $pConfig->getAddress());
        $this->assertEquals(2, $pConfig->getTimeout());
        $this->assertEquals(2, $pConfig->getInterval());
        $this->assertEquals([1, 2], $pConfig->getTags());
    }

    public function testGetConnection()
    {
        $pool = bean(DemoPool::class);
        $connection = $pool->getConnection();
        $connection2 = $pool->getConnection();

        $id = $connection->getConnection()->id;
        $id2 = $connection2->getConnection()->id;

        $this->assertNotEquals($id, $id2);

        $connection->release(true);
        $connection->receive();
        $connection3 = $pool->getConnection();
        $id3 = $connection3->getConnection()->id;
        $this->assertEquals($id, $id3);

        $connection2->release(true);
        $connection3->release(true);
    }

    public function testGetConnectionByCo()
    {
        go(function () {
            $this->testGetConnection();
        });
    }

    public function testPoolConfigTimeout()
    {
        $pConfig = App::getBean(EnvPoolConfig::class);
        $this->assertEquals($pConfig->getTimeout(), 2);

        $dConfig = App::getBean(DemoPoolConfig::class);
        $this->assertEquals($dConfig->getTimeout(), 0.5);
    }
}
