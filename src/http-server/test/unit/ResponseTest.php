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
use SwoftTest\Http\Server\Testing\Controller\TestController;
use SwoftTest\Http\Server\Testing\MockRequest;

/**
 * Class ResponseTest
 *
 * @package SwoftTest\Http\Server\Unit
 */
class ResponseTest extends HttpServerTestCase
{
    public function testRoute(): void
    {
        /** @see TestController */
        $response = $this->mockServer->request(MockRequest::GET, '/fixture/test');

        $response->assertEqualJson(['data' => 'home']);
    }

    public function testCookie(): void
    {
        /** @see TestController */
        $response = $this->mockServer->request(MockRequest::GET, '/fixture/test/cookie');

        $this->assertNotEmpty($cks = $response->getCookie());
        $this->assertArrayHasKey('ck', $cks);
        $this->assertSame('ck=val', $cks['ck']);
    }

    public function testHtml(): void
    {
        /** @see TestController */
        $response = $this->mockServer->request(MockRequest::GET, '/fixture/test/htmlData');

        $this->assertNotEmpty($c = $response->getContent());
        $this->assertNotEmpty($hs = $response->getHeaders());
        $this->assertArrayHasKey(ContentType::KEY, $hs);
        $this->assertSame('text/html; charset=utf-8', $hs[ContentType::KEY]);
        $this->assertSame('text/html; charset=utf-8', $response->getHeader(ContentType::KEY));
        $this->assertSame('<h1>hello</h1>', $c);

        /** @see TestController */
        $response = $this->mockServer->request(MockRequest::GET, '/fixture/test/htmlContent');

        $this->assertNotEmpty($c = $response->getContent());
        $this->assertNotEmpty($hs = $response->getHeaders());
        $this->assertArrayHasKey(ContentType::KEY, $hs);
        $this->assertSame('text/html; charset=utf-8', $hs[ContentType::KEY]);
        $this->assertSame('<h1>hello</h1>', $c);
    }
}
