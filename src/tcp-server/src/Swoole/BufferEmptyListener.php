<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Co\Server as CoServer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\BufferEmptyInterface;

/**
 * Class BufferEmptyListener
 *
 * @Bean("bufferEmptyListener")
 *
 * @since 2.0
 */
class BufferEmptyListener implements BufferEmptyInterface
{
    /**
     * @param CoServer $server
     * @param int      $fd
     */
    public function onBufferEmpty(CoServer $server, int $fd): void
    {

    }
}
