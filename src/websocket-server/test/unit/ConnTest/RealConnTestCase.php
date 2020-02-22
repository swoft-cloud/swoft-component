<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit\ConnTest;

use PHPUnit\Framework\TestCase;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;
use Swoole\Coroutine\Http\Client;
use function strpos;
use const RUN_SERVER_TEST;

/**
 * Class RealConnTestCase
 */
abstract class RealConnTestCase extends TestCase
{
    use CommonTestAssertTrait;

    protected $host = '127.0.0.1';

    protected $port = 28308;

    protected function setUp()
    {
        parent::setUp();

        // env: RUN_SERVER_TEST=ws,tcp,http
        if (!RUN_SERVER_TEST || false === strpos(RUN_SERVER_TEST, 'ws')) {
            $this->markTestSkipped('RUN_SERVER_TEST is not contains "ws", skip tests');
        }
    }

    /**
     * @param string $path
     *
     * @return Client
     */
    protected function connectTo(string $path): Client
    {
        $client = new Client($this->host, $this->port);
        $client->upgrade($path);

        return $client;
    }

    /**
     * @param Client $client
     */
    protected function assertHandshakeResponse(Client $client): void
    {
        $this->assertSame(0, $client->errCode);
        $this->assertSame(101, $client->getStatusCode());

        // check headers
        $realHeaders = $client->getHeaders();
        $wantHeaders = [
            'upgrade'               => 'websocket',
            'connection'            => 'Upgrade',
            // 'sec-websocket-accept'  => 'bpQfxuAWdK4dMBvt+dBfPH1Ri34=',
            'sec-websocket-version' => '13',
            // 'server'                => 'swoole-http-server',
        ];

        $this->assertArrayHasKey('sec-websocket-accept', $realHeaders);

        foreach ($wantHeaders as $name => $value) {
            $this->assertArrayHasKey($name, $realHeaders);
            $this->assertSame($value, $realHeaders[$name]);
        }
    }
}
