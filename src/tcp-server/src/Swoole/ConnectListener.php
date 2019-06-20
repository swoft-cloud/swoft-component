<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Server\Swoole\ConnectInterface;
use Swoft\Tcp\Server\TcpServerEvent;
use Swoole\Server;

/**
 * Class ConnectListener
 *
 * @Bean()
 *
 * @since 2.0
 */
class ConnectListener implements ConnectInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     * @throws ContainerException
     */
    public function onConnect(Server $server, int $fd, int $reactorId): void
    {
        // Trigger event
        Swoft::trigger(TcpServerEvent::CONNECT, $server, $fd, $reactorId);
    }
}
