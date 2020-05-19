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

use Swoft\Exception\SwoftException;

/**
 * Class HeaderTest
 *
 * @since 2.0
 */
class HeaderTest extends HttpServerTestCase
{
    /**
     * @throws SwoftException
     */
    public function testHeaderLines(): void
    {
        $headers = [
            'user-agent'   => 'curl/7.29.0',
            'host'         => '127.0.0.1:18306',
            'content-type' => 'application/json',
            'accept'       => '*/*',
            'id'           => 1024,
            'id2'          => 2048
        ];

        $response = $this->mockServer->request('POST', '/testHeader/headerLines', [], $headers);
        $response->assertEqualJson($headers);
    }
}
