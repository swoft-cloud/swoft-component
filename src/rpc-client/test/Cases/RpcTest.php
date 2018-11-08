<?php

namespace SwoftTest\Rpc\Client;

use Swoft\Rpc\Client\Bean\Collector\ReferenceCollector;
use Swoft\Rpc\Client\Service\ServiceProxy;
use SwoftTest\Rpc\Testing\Clients\DemoServiceClient;
use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;
use SwoftTest\Rpc\Testing\Pool\Config\DemoServicePoolConfig;

class RpcTest extends AbstractTestCase
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

    public function testLongMessageByCo()
    {
        go(function () {
            $client = bean(DemoServiceClient::class);
            $string = 'Hi, Agnes! ';
            $str = $client->longMessage($string);
            $expect = '';
            for ($i = 0; $i < 50000; $i++) {
                $expect .= $string;
            }
            $this->assertEquals($expect, $str);
        });
    }

    public function testRpcServiceTimeout()
    {
        go(function () {
            $client = bean(DemoServiceClient::class);
            $id = rand(1000, 9999);
            $res = $client->get($id);
            $this->assertEquals('', $res);

            \co::sleep(1);

            go(function () {
                $client = bean(DemoServiceClient::class);
                $id = rand(1000, 9999);
                $res = $client->get($id);
                $this->assertEquals('', $res);
            });

            \co::sleep(1);

            go(function () {
                $client = bean(DemoServiceClient::class);
                $id = rand(1000, 9999);
                $res = $client->get($id);
                $this->assertEquals('', $res);
            });
        });
    }

    public function testRpcServerRestart()
    {
        go(function () {
            for ($i = 0; $i < 10; $i++) {
                if ($i == 1) {
                    // $cmd = 'php ' . alias('@root') . '/server.php -d';
                    // exec($cmd);
                    // \co::sleep(1);
                }

                $client = bean(DemoServiceClient::class);
                $res = $client->version();
                $this->assertEquals('1.0.0', $res);
                \co::sleep(1);
            }
        });
    }
}