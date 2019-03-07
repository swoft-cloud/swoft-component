<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Co\Server as CoServer;
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
     * @param CoServer $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onConnect(CoServer $server, int $fd, int $reactorId): void
    {

    }
}