<?php

namespace SwoftTest\Rpc\Client;

use Swoft\Rpc\Client\Bean\Collector\ReferenceCollector;
use Swoft\Rpc\Client\Service\ServiceProxy;
use SwoftTest\Rpc\Testing\Clients\DemoServiceClient;
use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;
use SwoftTest\Rpc\Testing\Pool\Config\DemoServicePoolConfig;

class DemoTest extends AbstractTestCase
{
    public function testDemo()
    {
        $this->assertTrue(true);
    }

    public function testVersion()
    {
        $client = bean(DemoServiceClient::class);
        $this->assertEquals('1.0.0', $client->version());
    }
}