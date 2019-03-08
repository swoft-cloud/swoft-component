<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoole\Server;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\BufferFullInterface;

/**
 * Class BufferFullListener
 *
 * @Bean("bufferFullListener")
 * @since 2.0
 */
class BufferFullListener implements BufferFullInterface
{
    /**
     * @param Server $server
     * @param int      $fd
     */
    public function onBufferFull(Server $server, int $fd): void
    {

    }
}