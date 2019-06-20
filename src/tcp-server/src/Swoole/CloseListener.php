<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Server\Swoole\CloseInterface;
use Swoft\Tcp\Server\TcpServerEvent;
use Swoole\Server;

/**
 * Class CloseListener
 *
 * @Bean()
 *
 * @since 2.0
 */
class CloseListener implements CloseInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     * @throws ContainerException
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        // Trigger event
        Swoft::trigger(TcpServerEvent::CLOSE, $server, $fd, $reactorId);
    }
}
