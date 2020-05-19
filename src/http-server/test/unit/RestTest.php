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
use Swoft\Stdlib\Helper\JsonHelper;
use SwoftTest\Http\Server\Testing\MockRequest;

/**
 * Class RestTest
 *
 * @since 2.0
 */
class RestTest extends HttpServerTestCase
{
    public function testList(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testRestUser', [], $headers, []);
        $response->assertEqualJson(['list']);
    }

    public function testCreate(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $data = [
            'name' => 'swoft',
            'desc' => 'swoft framework'
        ];

        $ext      = [
            'content' => JsonHelper::encode($data)
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testRest/user', [], $headers, [], $ext);
        $response->assertEqualJson($data);
    }

    public function testGetUser(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $data = [
            'getUser',
            126
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testRest/126', [], $headers, []);
        $response->assertEqualJson($data);


        $response = $this->mockServer->request(MockRequest::GET, '/testRest/notInit', [], $headers, []);
        $response->assertEqualJson(['getUser', 0]);
    }

    public function testGetBookFromUser(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $data = [
            'bookFromUser',
            126,
            129
        ];

        $response = $this->mockServer->request(MockRequest::GET, '/testRest/126/book/129', [], $headers, []);
        $response->assertEqualJson($data);
    }

    public function testDelete(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $data = [
            'delete',
            126
        ];

        $response = $this->mockServer->request(MockRequest::DELETE, '/testRest/126', [], $headers);
        $response->assertEqualJson($data);
    }

    public function testUpdate(): void
    {
        $headers = [
            ContentType::KEY => ContentType::JSON
        ];

        $data = [
            'name' => 'swoft',
            'desc' => 'swoft framework'
        ];

        $ext = [
            'content' => JsonHelper::encode($data)
        ];

        $response = $this->mockServer->request(MockRequest::PUT, '/testRest/126', [], $headers, [], $ext);

        $data['update'] = 'update';
        $data['uid']    = 126;
        $response->assertEqualJson($data);
    }
}
