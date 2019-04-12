<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Server\ServiceServerEvent;
use Swoft\Server\Swoole\CloseInterface;
use Swoole\Server;

/**
 * Class CloseListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class CloseListener implements CloseInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @throws \Swoft\Bean\Exception\ContainerException*
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        var_dump('close');

        \Swoft::trigger(ServiceServerEvent::CLOSE);
    }
}