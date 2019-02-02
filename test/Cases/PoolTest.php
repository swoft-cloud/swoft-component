<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Db\Cases;

use Swoft\App;
use SwoftTest\Db\Testing\Pool\DbEnvPoolConfig;
use SwoftTest\Db\Testing\Pool\DbPptPoolConfig;
use SwoftTest\Db\Testing\Pool\DbSlaveEnvPoolConfig;
use SwoftTest\Db\Testing\Pool\DbSlavePptConfig;
use SwoftTest\Db\Testing\Pool\OtherDbConfig;
use SwoftTest\Db\Testing\Pool\OtherDbPool;

/**
 * PoolTest
 */
class PoolTest extends AbstractTestCase
{
    public function testDbPpt()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(DbPptPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'master1');
        $this->assertEquals($pConfig->getProvider(), 'consul1');
        $this->assertEquals($pConfig->getTimeout(), 1);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:3301',
            '127.0.0.1:3301',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'random1');
        $this->assertEquals($pConfig->getMaxActive(), 1);
        $this->assertEquals($pConfig->getMaxIdel(), 1);
        $this->assertEquals($pConfig->isUseProvider(), true);
        $this->assertEquals($pConfig->getMaxWait(), 1);
    }

    public function testDbEnv()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(DbEnvPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'master2');
        $this->assertEquals($pConfig->getProvider(), 'consul2');
        $this->assertEquals($pConfig->getTimeout(), 2);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:3306/test?user=root&password=&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=&charset=utf8',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'random');
        $this->assertEquals($pConfig->getMaxActive(), 60);
        $this->assertEquals($pConfig->isUseProvider(), false);
        $this->assertEquals($pConfig->getMaxWait(), 10);
    }

    public function testDbSlavePpt()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(DbSlavePptConfig::class);
        $this->assertEquals($pConfig->getName(), 'slave1');
        $this->assertEquals($pConfig->getProvider(), 'consul1');
        $this->assertEquals($pConfig->getTimeout(), 1);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:3301',
            '127.0.0.1:3301',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'random1');
        $this->assertEquals($pConfig->getMaxActive(), 1);
        $this->assertEquals($pConfig->getMaxIdel(), 1);
        $this->assertEquals($pConfig->isUseProvider(), true);
        $this->assertEquals($pConfig->getMaxWait(), 1);
    }

    public function testDbSlaveEnv()
    {
        /* @var \Swoft\Pool\PoolProperties $pConfig */
        $pConfig = App::getBean(DbSlaveEnvPoolConfig::class);
        $this->assertEquals($pConfig->getName(), 'slave2');
        $this->assertEquals($pConfig->getProvider(), 'consul2');
        $this->assertEquals($pConfig->getTimeout(), 3);
        $this->assertEquals($pConfig->getUri(), [
            '127.0.0.1:3306/test?user=root&password=&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=&charset=utf8',
        ]);
        $this->assertEquals($pConfig->getBalancer(), 'random');
        $this->assertEquals($pConfig->getMaxActive(), 60);
        $this->assertEquals($pConfig->isUseProvider(), false);
        $this->assertEquals($pConfig->getMaxWait(), 10);
    }

    public function testOtherConfig()
    {
        $config = bean(OtherDbConfig::class);
        $this->assertTrue($config->isStrictType());
        $this->assertTrue($config->isFetchMode());
    }

    public function testMaxIdleTime()
    {
        $pool = App::getPool('idle.master');
        $connection = $pool->getConnection();
        $this->assertTrue($connection->check());
        $connection->release(true);
        $connection2 = $pool->getConnection();
        $this->assertTrue($connection2->check());
        $connection2->release(true);
    }

    public function testMaxIdleTimeByCo()
    {
        go(function () {
            $this->testMaxIdleTime();

            $pool = App::getPool('idle.master');
            $connection = $pool->getConnection();
            $this->assertTrue($connection->check());
            $connection->release(true);

            \co::sleep(2);

            $connection2 = $pool->getConnection();
            $this->assertFalse($connection2->check());
            $connection2->release(true);

            $connection3 = $pool->getConnection();
            $this->assertTrue($connection3->check());
            $connection2->release(true);
        });
    }
}
