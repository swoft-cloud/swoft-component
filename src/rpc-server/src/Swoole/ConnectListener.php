<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\ConnectInterface;
use Swoole\Server;

/**
 * Class ConnectListener
 *
 * @since 2.0
 *
 * @Bean("connectListener")
 */
class ConnectListener implements ConnectInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     */
    public function onConnect(Server $server, int $fd, int $reactorId): void
    {

    }
}