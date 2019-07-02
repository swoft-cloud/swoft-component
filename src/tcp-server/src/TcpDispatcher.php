<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoole\Server as SwServer;

/**
 * Class TcpDispatcher
 *
 * @since 2.0.3
 * @Bean("tcpDispatcher")
 */
class TcpDispatcher
{
    /**
     * @param SwServer $server
     * @param int      $fd
     */
    public function close(SwServer $server, int $fd): void
    {
        // TODO ...
    }
}
