<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoole\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\ConnectInterface;

/**
 * Class ConnectListener
 *
 * @Bean("connectListener")
 *
 * @since 2.0
 */
class ConnectListener implements ConnectInterface
{
    /**
     * @param Server $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onConnect(Server $server, int $fd, int $reactorId): void
    {

    }
}