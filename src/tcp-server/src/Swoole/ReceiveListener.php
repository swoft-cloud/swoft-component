<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoole\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\ReceiveInterface;

/**
 * Class ReceiveListener
 *
 * @Bean("tcpReceiveListener")
 *
 * @since 2.0
 */
class ReceiveListener implements ReceiveInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     */
    public function onReceive(Server $server, int $fd, int $reactorId, string $data): void
    {
    }
}
