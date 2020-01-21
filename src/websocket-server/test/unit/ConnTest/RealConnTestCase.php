<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit\ConnTest;

use PHPUnit\Framework\TestCase;
use Swoole\Coroutine\Http\Client;
use function strpos;
use const RUN_SERVER_TEST;

/**
 * Class RealConnTestCase
 */
abstract class RealConnTestCase extends TestCase
{
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
}
