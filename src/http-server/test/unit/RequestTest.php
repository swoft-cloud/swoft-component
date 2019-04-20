<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Unit;

/**
 * Class RequestTest
 *
 * @since 2.0
 */
class RequestTest extends TestCase
{
    public function testA()
    {
        $response = $this->mockServer->request('get', '/request/common');
        $response->assertSuccess();
    }
}