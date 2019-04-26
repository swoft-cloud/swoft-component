<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Unit;


use Swoft\Http\Message\ContentType;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Test\Http\MockRequest;

/**
 * Class RouteTest
 *
 * @since 2.0
 *
 */
class RouteTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testReturnType()
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/string');
        $response->assertEqualJson(['data' => 'string']);

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/array');
        $response->assertEqualJson(['arr']);

        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/null');
        $response->assertEqualContent('{}');
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testAcceptType()
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
        $response->assertEqualHeader(ContentType::KEY, ContentType::JSON);

        $headers  = [
            'accept' => ContentType::XML
        ];
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/data', [], $headers);
        $response->assertEqualContent(
            '<xml><name><![CDATA[swoft]]></name><desc><![CDATA[framework]]></desc></xml>'
        );
        $response->assertEqualHeader(ContentType::KEY, ContentType::XML);
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testRequestContentParser()
    {
        $data = [
            'name' => 'swoft',
            'desc' => 'framework'
        ];

        $headers = [
            ContentType::KEY => ContentType::XML
        ];

        $ext      = [
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
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testMethod()
    {
        $response = $this->mockServer->request(MockRequest::POST, '/testRoute/method');
        $response->assertEqualJson(['data' => 'post']);

        $response = $this->mockServer->request(MockRequest::PUT, '/testRoute/method');
        $response->assertEqualJson(['data' => 'post']);
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testNotSupportedMethod()
    {
        $response = $this->mockServer->request(MockRequest::GET, '/testRoute/method');
        $response->assertContainContent('Route not found');
    }
}