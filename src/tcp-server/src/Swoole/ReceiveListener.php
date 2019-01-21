<?php

namespace Swoft\Tcp\Server\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\ReceiveInterface;
use Swoft\Server\Swoole\SCoServerwooleServer;

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
     * @param SCoServerwooleServer $server
     * @param int                  $fd
     * @param int                  $reactorId
     * @param string               $data
     */
    public function onReceive(SCoServerwooleServer $server, int $fd, int $reactorId, string $data): void
    {
        // TODO: Implement onReceive() method.
    }
}