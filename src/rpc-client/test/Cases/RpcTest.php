<?php

namespace SwoftTest\RpcClient;

use Swoft\App;
use Swoft\Rpc\Client\Bean\Collector\ReferenceCollector;
use Swoft\Rpc\Client\Service\ServiceProxy;
use SwoftTest\RpcClient\Testing\Clients\Demo8098ServiceClient;
use SwoftTest\RpcClient\Testing\Clients\DemoServiceClient;
use SwoftTest\RpcClient\Testing\Lib\DemoServiceInterface;
use SwoftTest\RpcClient\Testing\Pool\Config\DemoServicePoolConfig;

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

        if (App::isCoContext()) {
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
            $cmd = 'php ' . alias('@root') . '/rpc_server.php -d';
            \co::exec($cmd);
            \co::sleep(1);

            $client = bean(DemoServiceClient::class);
            $res = $client->version();
            $this->assertEquals('1.0.0', $res);
        }
    }
}