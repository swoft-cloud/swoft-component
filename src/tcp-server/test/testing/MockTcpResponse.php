<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Tcp\Server\Response;
use Swoole\Server;

/**
 * Class MockTcpResponse
 *
 * @Bean()
 */
class MockTcpResponse extends Response
{
    /**
     * @var string
     */
    public $responseBody = '';

    protected function doSend(Server $server, string $content): void
    {
        $this->responseBody = $content;
    }
}
