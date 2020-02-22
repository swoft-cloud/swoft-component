<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
