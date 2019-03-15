<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoole\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\CloseInterface;

/**
 * Class CloseListener
 *
 * @Bean("closeListener")
 *
 * @since 2.0
 */
class CloseListener implements CloseInterface
{
    /**
     * @param Server $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {

    }
}