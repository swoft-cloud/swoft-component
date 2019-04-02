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
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        \Swoft::trigger(ServiceServerEvent::CLOSE);
        
        var_dump('close');
    }
}