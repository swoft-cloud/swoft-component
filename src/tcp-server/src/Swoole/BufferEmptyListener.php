<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Swoole;

use Swoole\Server;
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
     * @param Server $server
     * @param int      $fd
     */
    public function onBufferEmpty(Server $server, int $fd): void
    {

    }
}
