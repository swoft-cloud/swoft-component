<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Co\Server as CoServer;
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
     * @param CoServer $server
     * @param int      $fd
     */
    public function onBufferFull(CoServer $server, int $fd): void
    {

    }
}