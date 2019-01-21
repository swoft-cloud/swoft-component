<?php

namespace SwoftTest\Rpc\Client;

use Swoft\Rpc\Client\Bean\Collector\ReferenceCollector;
use Swoft\Rpc\Client\Service\ServiceProxy;
use SwoftTest\Rpc\Testing\Clients\Demo8098ServiceClient;
use SwoftTest\Rpc\Testing\Clients\DemoServiceClient;
use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;
use SwoftTest\Rpc\Testing\Pool\Config\DemoServicePoolConfig;

class RpcTest extends AbstractTestCase
{
    public function testDemo()
    {
        $this->assertTrue(true);
    }

    public function testRpc()
    {
        $client = bean(DemoServiceClient::class);
        $this->assertEquals('1.0.0', $client->version());

        // SyncServiceConnection not timeout
        $client = bean(DemoServiceClient::class);
        $id = rand(1000, 9999);
        $res = $client->get($id);
        $this->assertEquals($id, $res);

        go(function () {
            // Test Long Message
            $client = bean(DemoServiceClient::class);
            $string = 'Hi, Agnes! ';
            $str = $client->longMessage($string);
            $expect = '';
            for ($i = 0; $i < 50000; $i++) {
                $expect .= $string;
            }
            $this->assertEquals($expect, $str);

            // Test Tcp Timeout
            go(function () {
                $client = bean(DemoServiceClient::class);
                $id = rand(1000, 9999);
                $res = $client->get($id);
                $this->assertEquals('', $res);
            });

            \co::sleep(2);

            // Test Rpc Server Restart
            $cmd = 'php ' . alias('@root') . '/server.php -d';
            \co::exec($cmd);
            \co::sleep(1);

            $client = bean(DemoServiceClient::class);
            $res = $client->version();
            $this->assertEquals('1.0.0', $res);
        });
    }
}