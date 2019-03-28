<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\ReceiveInterface;
use Swoole\Server;

/**
 * Class ReceiveListener
 *
 * @since 2.0
 *
 * @Bean()
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