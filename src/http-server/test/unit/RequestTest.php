<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Unit;

use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Stdlib\Helper\JsonHelper;
use SwoftTest\Http\Server\Testing\MockRequest;

/**
 * Class RequestTest
 *
 * @since 2.0
 */
class RequestTest extends HttpServerTestCase
{
    public function testPost(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $params = [
            'name' => 'swoft',
            'desc' => 'swoft framework'
        ];

        $mockRequest = $this->mockServer->mockRequest(MockRequest::POST, '/testRest/user', $params, $headers);

        $request = Request::new($mockRequest);
        $this->assertRequestHeaders($request->getHeaders());

        $this->assertEquals(MockRequest::POST, $request->getMethod());
        $this->assertEquals($request->getHeader(ContentType::KEY)[0], ContentType::JSON);
        $this->assertEquals('/testRest/user', $request->getUri()->getPath());
        $this->assertGreaterThan(0, $request->getRequestTime());
        $this->assertEquals($request->getParsedBody(), $params);
        $this->assertEquals($request->post(), $params);
        $this->assertTrue($request->isPost());
        $this->assertEquals($request->post('name'), 'swoft');
        $this->assertEquals($request->input('name'), 'swoft');
        $this->assertEquals($request->post('desc'), 'swoft framework');
        $this->assertEquals($request->post('desc2', 'desc2'), 'desc2');

        $this->assertEquals($request->get('name'), null);
    }

    public function testPutContent(): void
    {
        $data = [
            'name' => 'swoft',
            'desc' => 'swoft framework'
        ];

        $ext = [
            'content' => JsonHelper::encode($data)
        ];

        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $mockRequest = $this->mockServer->mockRequest(MockRequest::PUT, '/testRest/user', [], $headers, [], $ext);

        $request = Request::new($mockRequest);
        $this->assertRequestHeaders($request->getHeaders());

        $this->assertEquals(MockRequest::PUT, $request->getMethod());
        $this->assertEquals($request->getHeader(ContentType::KEY)[0], ContentType::JSON);
        $this->assertEquals('/testRest/user', $request->getUri()->getPath());
        $this->assertGreaterThan(0, $request->getRequestTime());
        $this->assertEquals($request->getParsedBody(), $data);
        $this->assertEquals($request->post(), $data);
        $this->assertTrue($request->isPut());
        $this->assertEquals($request->post('name'), 'swoft');
        $this->assertEquals($request->input('name'), 'swoft');
        $this->assertEquals($request->post('desc'), 'swoft framework');
        $this->assertEquals($request->post('desc2', 'desc2'), 'desc2');

        $this->assertEquals($request->get('name'), null);
    }

    public function testGet(): void
    {
        $data = [
            'name' => 'swoft',
            'desc' => 'swoft framework'
        ];

        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $mockRequest = $this->mockServer->mockRequest(MockRequest::GET, '/testRest/user', $data, $headers);

        $request = Request::new($mockRequest);
        $this->assertRequestHeaders($request->getHeaders());

        $this->assertEquals(MockRequest::GET, $request->getMethod());
        $this->assertEquals($request->getHeader(ContentType::KEY)[0], ContentType::JSON);
        $this->assertEquals('/testRest/user', $request->getUri()->getPath());
        $this->assertGreaterThan(0, $request->getRequestTime());
        $this->assertEquals($request->getParsedBody(), []);
        $this->assertEquals($request->get(), $data);
        $this->assertEquals($request->getQueryParams(), $data);
        $this->assertTrue($request->isGet());
        $this->assertEquals($request->get('name'), 'swoft');
        $this->assertEquals($request->get('desc'), 'swoft framework');
        $this->assertEquals($request->get('desc2', 'desc2'), 'desc2');

        $this->assertEquals($request->post('name'), null);
        $this->assertEquals($request->input('name'), 'swoft');
    }

    protected function assertRequestHeaders(array $headers): void
    {
        $requestHeaders = [
            'user-agent'   => ['swoft/2.0.0'],
            'host'         => ['127.0.0.1:18306'],
            'accept'       => ['*/*'],
            'content-type' => ['application/json'],
        ];

        $this->assertEquals($requestHeaders, $headers);
    }
}
