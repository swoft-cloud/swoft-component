<?php

namespace SwoftTest\Rpc\Client;

use SwoftTest\Rpc\Testing\Clients\DemoServiceClient;

class DemoTest extends AbstractTestCase
{
    public function testDemo()
    {
        $this->assertTrue(true);
    }

    public function testVersion()
    {
        $client = bean(DemoServiceClient::class);
        $version = $client->version();
        var_dump($version);
    }
}