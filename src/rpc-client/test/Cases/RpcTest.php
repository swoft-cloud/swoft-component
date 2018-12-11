<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\RpcClient\Cases;

use Swoft\App;
use SwoftTest\RpcClient\Testing\Clients\DemoServiceClient;

class RpcTest extends AbstractTestCase
{
    public function testDemo()
    {
        $this->assertTrue(true);
    }

    public function testLongMessage()
    {
        $this->assertTrue(true);
        if (App::isCoContext()) {
            $client = bean(DemoServiceClient::class);
            $string = 'Hi, Agnes! ';
            $str = $client->longMessage($string);
            $expect = '';
            for ($i = 0; $i < 50000; $i++) {
                $expect .= $string;
            }
            $this->assertEquals($expect, $str);
        }
    }

    public function testTcpTimeout()
    {
        $client = bean(DemoServiceClient::class);
        $id = rand(1000, 9999);
        $res = $client->get($id);
        if (App::isCoContext()) {
            $this->assertEquals('', $res);
        } else {
            // SyncServiceConnection not timeout
            $this->assertEquals($id, $res);
        }
    }

    public function testRpcReconnect()
    {
        if (App::isCoContext()) {
            $cmd = 'php ' . alias('@root') . '/rpc_server.php -d';

            \co::exec($cmd);
            \co::sleep(5);

            $client = bean(DemoServiceClient::class);
            $res = $client->version();
            $this->assertEquals('1.0.0', $res);
        }

        $this->assertTrue(true);
    }

    public function testRpc()
    {
        $client = bean(DemoServiceClient::class);
        $this->assertEquals('1.0.0', $client->version());
    }
}
