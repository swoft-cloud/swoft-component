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
use Swoft\Http\Message\Testing\Base\Response;
use Swoft\HttpClient\Client;

class CoroutineClientTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function get()
    {
        $client = new Client();
        $method = 'GET';

        // Http
        /** @var Response $response */
        $response = $client->request($method, '', [
            'base_uri' => 'http://www.swoft.org',
            '_options' => $this->getOptions(),
            'headers' => [
                'Accept' => 'text/html'
            ],
        ])->getResponse();

        $response->assertSuccessful()->assertSee('Swoft 官网');

        // Https
        /** @var Response $response */
        $response = $client->request($method, '', [
            'base_uri' => 'https://www.swoft.org',
            '_options' => $this->getOptions(),
        ])->getResponse();
        $response->assertSuccessful()->assertSee('Swoft 官网');

        /** @var Response $response */
        $response = $client->request($method, '/?a=1', [
            'base_uri' => 'http://echo.swoft.org',
            '_options' => $this->getOptions()
        ])->getResponse();
        $response->assertSuccessful();
        $this->assertJson($response->getBody()->getContents());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertSame('a=1', $body['uri']['query']);
    }

    /**
     * @test
     */
    public function postRawContent()
    {
        $client = new Client();

        /**
         * Post raw body
         */
        $body = 'raw';
        $method = 'POST';
        /** @var Response $response */
        $response = $client->request($method, '', [
            'base_uri' => 'http://echo.swoft.org',
            'body' => $body,
            '_options' => $this->getOptions()
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
    }

    /**
     * @test
     */
    public function postFormParams()
    {
        $client = new Client();

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
            '_options' => $this->getOptions(),
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
    }

    /**
     * @test
     */
    public function postJson()
    {
        $client = new Client();

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
            '_options' => $this->getOptions(),
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
    }

    /**
     * @test
     */
    public function putJson()
    {
        $client = new Client();

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
            '_options' => $this->getOptions(),
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
    }

    /**
     * @test
     */
    public function deleteJson()
    {
        $client = new Client();

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
            '_options' => $this->getOptions(),
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
    }

    /**
     * @test
     */
    public function batch()
    {
        $client = new Client([
            'base_uri' => 'https://www.swoole.com/',
        ]);

        $request1 = $client->request('GET', '', ['_options' => $this->getOptions()]);
        $request2 = $client->request('GET', '', ['_options' => $this->getOptions()]);
        $request3 = $client->request('GET', '', ['_options' => $this->getOptions()]);

        /** @var Response $response1 */
        $response1 = $request1->getResponse();

        /** @var Response $response2 */
        $response2 = $request2->getResponse();
        /** @var Response $response3 */
        $response3 = $request3->getResponse();

        $response1->assertSuccessful()->assertSee('Swoole');
        $response2->assertSuccessful()->assertSee('Swoole');
        $response3->assertSuccessful()->assertSee('Swoole');
    }

    /**
     * @test
     */
    public function defaultUserAgent()
    {
        $client = new Client();
        if (App::isCoContext()) {
            $expected = sprintf('Swoft/%s PHP/%s', SWOFT_VERSION, PHP_VERSION);
        } else {
            $expected = sprintf('Swoft/%s curl/%s PHP/%s', SWOFT_VERSION, curl_version()['version'], PHP_VERSION);
        }
        $this->assertSame($expected, $client->getDefaultUserAgent());
    }

    /**
     * @test
     */
    public function baseUri()
    {
        // 测试base_uri传入的域名带path时，会主动过滤path
        $client = new Client([
            'base_uri' => 'http://echo.swoft.org/test',
            '_options' => $this->getOptions()
        ]);

        $res = $client->get('/info')->getResult();
        $this->assertSame(['message' => 'Route not found for /info'], JsonHelper::decode($res, true));

        $client = new Client([
            'base_uri' => 'http://echo.swoft.org?id=xxx',
            '_options' => $this->getOptions()
        ]);

        $res = $client->get('/?id2=yyy&id3=zzz')->getResult();
        $res = JsonHelper::decode($res, true);
        $this->assertSame('id2=yyy&id3=zzz', $res['uri']['query']);
    }

    /**
     * @test
     */
    public function queryNotInvalid()
    {
        $client = new Client([
            'base_uri' => 'http://echo.swoft.org',
            '_options' => $this->getOptions()
        ]);

        $res = $client->get('0')->getResult();
        $this->assertSame(['message' => 'Route not found for /0'], JsonHelper::decode($res, true));

        $client = new Client([
            'base_uri' => 'http://echo.swoft.org',
            '_options' => $this->getOptions()
        ]);

        $res = $client->get(0)->getResult();
        $res = JsonHelper::decode($res, true);
        $this->assertSame('/', $res['uri']['path']);

        $res = $client->get(' ')->getResult();
        $this->assertSame(['message' => 'Route not found for / '], JsonHelper::decode($res, true));

        $res = $client->get(123)->getResult();
        $this->assertSame(['message' => 'Route not found for /123'], JsonHelper::decode($res, true));
    }
}
