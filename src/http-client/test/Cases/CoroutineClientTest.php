<?php

namespace SwoftTest\HttpClient;

use Swoft\App;
use Swoft\Helper\JsonHelper;
use Swoft\Http\Message\Testing\Base\Response;
use Swoft\HttpClient\Client;

/**
 * @uses      CoroutineClientTest
 * @version   2017-11-22
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CoroutineClientTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function get()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');
            $method = 'GET';

            // Http
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'http://www.swoft.org',
                '_options' => $this->options,
                'headers' => [
                    'Accept' => 'text/html'
                ],
            ])->getResponse();

            $response->assertSuccessful()->assertSee('Swoft 官网');

            // Https
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'https://www.swoft.org',
                '_options' => $this->options,
            ])->getResponse();
            $response->assertSuccessful()->assertSee('Swoft 官网');

            /** @var Response $response */
            $response = $client->request($method, '/?a=1', [
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options
            ])->getResponse();
            $response->assertSuccessful();
            $this->assertJson($response->getBody()->getContents());
            $body = json_decode($response->getBody()->getContents(), true);
            $this->assertEquals('a=1', $body['uri']['query']);

        });
    }

    /**
     * @test
     */
    public function postRawContent()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');

            /**
             * Post raw body
             */
            $body = 'raw';
            $method = 'POST';
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'http://echo.swoft.org',
                'body' => $body,
                '_options' => $this->options
            ])->getResponse();
            $response->assertSuccessful()
                ->assertHeader('Content-Type', 'application/json')
                ->assertJsonFragment([
                    'Host' => 'echo.swoft.org',
                    'Content-Type' => 'text/plain',
                ])->assertJsonFragment([
                    'uri' => [
                        'scheme' => 'http',
                        'userInfo' => '',
                        'host' => 'echo.swoft.org',
                        'port' => 80,
                        'path' => '/',
                        'query' => '',
                        'fragment' => '',
                    ],
                ])->assertJsonFragment([
                    'method' => $method,
                    'body' => $body,
                ]);

        });
    }

    /**
     * @test
     */
    public function postFormParams()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');

            $body = [
                'string' => 'value',
                'int' => 1,
                'boolean' => true,
                'float' => 1.2345,
                'array' => [
                    '1',
                    '2',
                ],
            ];
            $method = 'POST';
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options,
                'form_params' => $body,
            ])->getResponse();
            $response->assertSuccessful()
                ->assertHeader('Content-Type', 'application/json')
                ->assertJsonFragment([
                    'Host' => 'echo.swoft.org',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])->assertJsonFragment([
                    'uri' => [
                        'scheme' => 'http',
                        'userInfo' => '',
                        'host' => 'echo.swoft.org',
                        'port' => 80,
                        'path' => '/',
                        'query' => '',
                        'fragment' => '',
                    ],
                ])->assertJsonFragment([
                    'method' => $method,
                    'body' => http_build_query($body),
                ]);

        });
    }

    /**
     * @test
     */
    public function postJson()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');

            $body = [
                'string' => 'value',
                'int' => 1,
                'boolean' => true,
                'float' => 1.2345,
                'array' => [
                    '1',
                    '2',
                ],
            ];
            $method = 'POST';
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options,
                'json' => $body,
            ])->getResponse();
            $response->assertSuccessful()
                ->assertHeader('Content-Type', 'application/json')
                ->assertJsonFragment([
                    'Host' => 'echo.swoft.org',
                    'Content-Type' => 'application/json',
                ])->assertJsonFragment([
                    'uri' => [
                        'scheme' => 'http',
                        'userInfo' => '',
                        'host' => 'echo.swoft.org',
                        'port' => 80,
                        'path' => '/',
                        'query' => '',
                        'fragment' => '',
                    ],
                ])->assertJsonFragment([
                    'method' => $method,
                    'body' => json_encode($body),
                ]);

        });
    }

    /**
     * @test
     */
    public function putJson()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');

            $body = [
                'string' => 'value',
                'int' => 1,
                'boolean' => true,
                'float' => 1.2345,
                'array' => [
                    '1',
                    '2',
                ],
            ];
            $method = 'PUT';
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options,
                'json' => $body,
            ])->getResponse();
            $response->assertSuccessful()
                ->assertHeader('Content-Type', 'application/json')
                ->assertJsonFragment([
                    'Host' => 'echo.swoft.org',
                    'Content-Type' => 'application/json',
                ])->assertJsonFragment([
                    'uri' => [
                        'scheme' => 'http',
                        'userInfo' => '',
                        'host' => 'echo.swoft.org',
                        'port' => 80,
                        'path' => '/',
                        'query' => '',
                        'fragment' => '',
                    ],
                ])->assertJsonFragment([
                    'method' => $method,
                    'body' => json_encode($body),
                ]);

        });
    }

    /**
     * @test
     */
    public function deleteJson()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');

            $body = [
                'string' => 'value',
                'int' => 1,
                'boolean' => true,
                'float' => 1.2345,
                'array' => [
                    '1',
                    '2',
                ],
            ];
            $method = 'DELETE';
            /** @var Response $response */
            $response = $client->request($method, '', [
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options,
                'json' => $body,
            ])->getResponse();
            $response->assertSuccessful()
                ->assertHeader('Content-Type', 'application/json')
                ->assertJsonFragment([
                    'Host' => 'echo.swoft.org',
                    'Content-Type' => 'application/json',
                ])->assertJsonFragment([
                    'uri' => [
                        'scheme' => 'http',
                        'userInfo' => '',
                        'host' => 'echo.swoft.org',
                        'port' => 80,
                        'path' => '/',
                        'query' => '',
                        'fragment' => '',
                    ],
                ])->assertJsonFragment([
                    'method' => $method,
                    'body' => json_encode($body),
                ]);

        });
    }

    /**
     * @test
     */
    public function batch()
    {
        go(function () {
            $client = new Client([
                'base_uri' => 'http://www.swoft.org',
            ]);
            $client->setAdapter('coroutine');

            $request1 = $client->request('GET', '', ['_options' => $this->options]);
            $request2 = $client->request('GET', '', ['_options' => $this->options]);
            $request3 = $client->request('GET', '', ['_options' => $this->options]);

            /** @var Response $response1 */
            $response1 = $request1->getResponse();
            /** @var Response $response2 */
            $response2 = $request2->getResponse();
            /** @var Response $response3 */
            $response3 = $request3->getResponse();

            $response1->assertSuccessful()->assertSee('Swoft 官网');
            $response2->assertSuccessful()->assertSee('Swoft 官网');
            $response3->assertSuccessful()->assertSee('Swoft 官网');

        });
    }

    /**
     * @test
     */
    public function defaultUserAgent()
    {
        go(function () {
            $client = new Client();
            $client->setAdapter('coroutine');
            $expected = sprintf('Swoft/%s PHP/%s', App::version(), PHP_VERSION);
            $this->assertEquals($expected, $client->getDefaultUserAgent());

        });
    }

    /**
     * @test
     */
    public function baseUri()
    {
        go(function () {
            // 测试base_uri传入的域名带path时，会主动过滤path
            $client = new Client([
                'base_uri' => 'http://echo.swoft.org/test',
                '_options' => $this->options
            ]);

            $res = $client->get('/info')->getResult();
            $this->assertEquals(['message' => 'Route not found for /info'], JsonHelper::decode($res, true));

            $client = new Client([
                'base_uri' => 'http://echo.swoft.org?id=xxx',
                '_options' => $this->options
            ]);

            $res = $client->get('/?id2=yyy&id3=zzz')->getResult();
            $res = JsonHelper::decode($res, true);
            $this->assertEquals('id2=yyy&id3=zzz', $res['uri']['query']);
        });
    }

    /**
     * @test
     */
    public function queryNotInvalid()
    {
        go(function () {
            $client = new Client([
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options
            ]);

            $res = $client->get('0')->getResult();
            $this->assertEquals(['message' => 'Route not found for /0'], JsonHelper::decode($res, true));

            $client = new Client([
                'base_uri' => 'http://echo.swoft.org',
                '_options' => $this->options
            ]);

            $res = $client->get(0)->getResult();
            $res = JsonHelper::decode($res, true);
            $this->assertEquals('/', $res['uri']['path']);

            $res = $client->get(' ')->getResult();
            $this->assertEquals(['message' => 'Route not found for / '], JsonHelper::decode($res, true));

            $res = $client->get(123)->getResult();
            $this->assertEquals(['message' => 'Route not found for /123'], JsonHelper::decode($res, true));
        });
    }
}
