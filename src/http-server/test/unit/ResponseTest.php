<?php declare(strict_types=1);

namespace SwoftTest\Http\Server\Unit;

use SwoftTest\Http\Server\Testing\MockRequest;

/**
 * Class ResponseTest
 *
 * @package SwoftTest\Http\Server\Unit
 */
class ResponseTest extends TestCase
{
    public function testCookie(): void
    {
        $response = $this->mockServer->request(MockRequest::GET, '/fixture/test/cookie');

        $this->assertNotEmpty($cks = $response->getCookie());
        $this->assertArrayHasKey('ck', $cks);
        $this->assertSame('ck=val', $cks['ck']);
    }
}
