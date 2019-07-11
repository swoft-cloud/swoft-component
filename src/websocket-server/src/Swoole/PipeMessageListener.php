<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\PipeMessageInterface;
use Swoole\Server;

/**
 * Class PipeMessageListener
 *
 * @Bean()
 */
class PipeMessageListener implements PipeMessageInterface
{
    /**
     * Pipe message event
     *
     * @param Server $server
     * @param int    $srcWorkerId
     * @param mixed  $message
     */
    public function onPipeMessage(Server $server, int $srcWorkerId, $message): void
    {
        // TODO: Implement onPipeMessage() method.
    }
}
