<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\HttpClient\Cases;

use Swoft\App;
use Swoft\Helper\JsonHelper;
use Swoft\HttpClient\Adapter;
use Swoft\HttpClient\Client;

class ClientTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function result()
    {
        $request = function () {
            $client = new Client();
            return $client->request('GET', '', [
                'base_uri' => $this->baseUri,
            ]);
        };

        $json = JsonHelper::decode($request()->getResponse()->getBody()->getContents(), true);
        $json2 = JsonHelper::decode($request()->getResult(), true);

        // FIXME : Travis Ci中，调用多次时，出口IP不同
        unset($json['headers']['X-Real-Ip'], $json2['headers']['X-Real-Ip'], $json['headers']['X-Forwarded-For'], $json2['headers']['X-Forwarded-For']);

        $this->assertSame($json, $json2);
    }

    /**
     * @test
     */
    public function adapters()
    {
        $client = new Client([
            'adapter' => 'co'
        ]);
        $this->assertInstanceOf(Adapter\CoroutineAdapter::class, $client->getAdapter());
        $client = new Client([
            'adapter' => 'coroutine'
        ]);
        $this->assertInstanceOf(Adapter\CoroutineAdapter::class, $client->getAdapter());
        $client = new Client([
            'adapter' => 'swoole'
        ]);
        $this->assertInstanceOf(Adapter\CoroutineAdapter::class, $client->getAdapter());

        $client = new Client([
            'adapter' => 'curl'
        ]);
        $this->assertInstanceOf(Adapter\CurlAdapter::class, $client->getAdapter());
        $client = new Client([
            'adapter' => 'php'
        ]);
        $this->assertInstanceOf(Adapter\CurlAdapter::class, $client->getAdapter());

        $client = new Client();
        if (App::isCoContext()) {
            $this->assertInstanceOf(Adapter\CoroutineAdapter::class, $client->getAdapter());
        } else {
            $this->assertInstanceOf(Adapter\CurlAdapter::class, $client->getAdapter());
        }
    }

    /**
     * @test
     */
    public function addAdapters()
    {
        $client = new Client();
        $client->addAdapter('test', Adapter\CurlAdapter::class);
        $client->setAdapter('test');
        $this->assertInstanceOf(Adapter\CurlAdapter::class, $client->getAdapter());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function adapterNotExist()
    {
        $client = new Client();
        $client->setAdapter('notExistAdapter');
    }
}
