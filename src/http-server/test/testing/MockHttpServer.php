<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing;

use RuntimeException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Http\Message\Request as ServerRequest;
use Swoft\Http\Message\Response as ServerResponse;
use Swoft\Http\Server\HttpDispatcher;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class MockHttpServer
 *
 * @since 2.0
 *
 * @Bean()
 */
class MockHttpServer
{
    /**
     * @param string $method
     * @param string $uri
     * @param array  $params
     * @param array  $headers
     * @param array  $cookies
     * @param array  $ext
     *
     * @return MockResponse
     */
    public function request(
        string $method,
        string $uri,
        array $params = [],
        array $headers = [],
        array $cookies = [],
        array $ext = []
    ): MockResponse {
        $request = $this->mockRequest($method, $uri, $params, $headers, $cookies, $ext);

        $response = $this->onRequest($request, new MockResponse);
        $response = $response->getCoResponse();

        if (!$response instanceof MockResponse) {
            throw new RuntimeException('Mock request return is not MockResponse');
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $params
     * @param array  $headers
     * @param array  $cookies
     * @param array  $ext
     *
     * @return Request
     */
    public function mockRequest(
        string $method,
        string $uri,
        array $params = [],
        array $headers = [],
        array $cookies = [],
        array $ext = []
    ): Request {
        $servers = [
            'request_method' => $method,
            'request_uri'    => $uri,
            'path_info'      => $uri,
        ];

        $request = MockRequest::new($servers, $headers, $cookies, $params);

        $content = $ext['content'] ?? '';
        $request->setContent($content);

        return $request;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return ServerResponse
     */
    public function onRequest(Request $request, Response $response): ServerResponse
    {
        $psrRequest  = ServerRequest::new($request);
        $psrResponse = ServerResponse::new($response);

        /* @var HttpDispatcher $dispatcher */
        $dispatcher = BeanFactory::getSingleton('httpDispatcher');

        $dispatcher->dispatch($psrRequest, $psrResponse);

        return $psrResponse;
    }
}
