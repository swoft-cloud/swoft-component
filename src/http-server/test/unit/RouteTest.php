<?php declare(strict_types=1);

namespace SwoftTest\Http\Server\Unit;

use Swoft\Exception\SwoftException;
use Swoft\Http\Message\ContentType;
use Swoft\Stdlib\Helper\JsonHelper;
use SwoftTest\Http\Server\Testing\MockRequest;

/**
 * Class RouteTest
 *
 * @since 2.0
 *
 */
class RouteTest extends TestCase
{
    /**
     * @throws SwoftException
     */
    public function testReturnType(): void
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/string');
        $response->assertEqualJson(['data' => 'string']);

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/array');
        $response->assertEqualJson(['arr']);

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/null');
        $response->assertEqualContent('{}');
    }

    /**
     * @throws SwoftException
     */
    public function testAcceptType(): void
    {
        $data = [
            'name' => 'swoft',
            'desc' => 'framework'
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/data');
        $response->assertEqualJson($data);

        $headers  = [
            'accept' => ContentType::JSON
        ];
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/data', [], $headers);
        $response->assertEqualJson($data);
        $response->assertEqualHeader(ContentType::KEY, $response->getHeaderKey(ContentType::KEY));

        $headers  = [
            'accept' => ContentType::XML
        ];
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/data', [], $headers);
        $response->assertEqualContent('<xml><name><![CDATA[swoft]]></name><desc><![CDATA[framework]]></desc></xml>');
        $response->assertEqualHeader(ContentType::KEY, $response->getHeaderKey(ContentType::KEY));
    }

    /**
     * @throws SwoftException
     */
    public function testRequestContentParser(): void
    {
        $data = [
            'name' => 'swoft',
            'desc' => 'framework'
        ];

        $headers = [
            ContentType::KEY => ContentType::XML
        ];

        $ext = [
            'content' => '<xml><name><![CDATA[swoft]]></name><desc><![CDATA[framework]]></desc></xml>'
        ];

        $response = $this->mockServer->request(MockRequest::POST, '/testRoute/parser', [], $headers, [], $ext);
        $response->assertEqualJson($data);


        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $ext      = [
            'content' => JsonHelper::encode($data, JSON_UNESCAPED_UNICODE)
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testRoute/parser', [], $headers, [], $ext);
        $response->assertEqualJson($data);
    }

    /**
     * @throws SwoftException
     */
    public function testMethod(): void
    {
        $response = $this->mockServer->request(MockRequest::POST, '/testRoute/method');
        $response->assertEqualJson(['data' => 'post']);

        $response = $this->mockServer->request(MockRequest::PUT, '/testRoute/method');
        $response->assertEqualJson(['data' => 'post']);
    }

    /**
     * @throws SwoftException
     */
    public function testNotSupportedMethod(): void
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/method');
        $response->assertContainContent('Route not found');
    }

    /**
     * @throws SwoftException
     */
    public function testRouteCNParam(): void
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/search/tom');

        $this->assertSame('{"data":"tom"}', $response->getContent());

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/search/汤姆');

        $this->assertSame('{"data":"汤姆"}', $response->getContent());

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/search/中国');

        $this->assertSame('{"data":"中国"}', $response->getContent());
    }

    /**
     * @throws SwoftException
     */
    public function testTrait(): void
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/traitMethod');
        $response->assertEqualJson(['traitMethod']);
    }

    /**
     * @throws SwoftException
     */
    public function testBaseAction(): void
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/baseMethod');
        $response->assertEqualJson(['baseMethod']);
    }
}
