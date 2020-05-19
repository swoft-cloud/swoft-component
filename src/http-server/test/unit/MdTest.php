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
use SwoftTest\Http\Server\Testing\MockRequest;

/**
 * Class MdTest
 *
 * @since 2.0
 */
class MdTest extends HttpServerTestCase
{
    public function testMethod()
    {
        $headers = [
            'Method-md'      => 'ok',
            'Method-md2'     => 'ok',
            'Method-md3'     => 'ok',
            'Controller-md'  => 'ok',
            'Controller-md2' => 'ok',
            'Controller-md3' => 'ok',
            ContentType::KEY => ContentType::JSON
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testMw/method');
        $response->assertEqualJson(['method']);
        $headers[ContentType::KEY] = $response->getHeaderKey(ContentType::KEY);
        $response->assertEqualHeaders($headers);
    }

    public function testMethod2()
    {
        $headers = [
            'Method-md2'     => 'ok',
            'Controller-md'  => 'ok',
            'Controller-md2' => 'ok',
            'Controller-md3' => 'ok',
            ContentType::KEY => ContentType::JSON
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testMw/method2');
        $response->assertEqualJson(['method2']);
        $headers[ContentType::KEY] = $response->getHeaderKey(ContentType::KEY);
        $response->assertEqualHeaders($headers);
    }

    public function testMethod23()
    {
        $headers = [
            'Controller-md'  => 'ok',
            'Controller-md2' => 'ok',
            'Controller-md3' => 'ok',
            ContentType::KEY => ContentType::JSON
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testMw/method3');
        $response->assertEqualJson(['method3']);
        $headers[ContentType::KEY] = $response->getHeaderKey(ContentType::KEY);
        $response->assertEqualHeaders($headers);
    }
}
