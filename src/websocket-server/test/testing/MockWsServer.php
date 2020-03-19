<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\WebSocketServer;
use function time;

/**
 * Class MockWsServer
 *
 * @since 2.0.3
 * @Bean()
 */
class MockWsServer extends WebSocketServer
{
    public function handshake(): bool
    {
        return true;
    }

    public function getClientInfo(int $fd): array
    {
        return [
            'in_testing'   => 'yes',
            'remote_ip'    => '127.0.0.1',
            'remote_port'  => '1000',
            'connect_time' => time(),
        ];
    }
}
