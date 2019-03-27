<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Websocket\Frame;
use Swoole\Websocket\Server;

/**
 * Interface MessageInterface
 *
 * @since 2.0
 */
interface MessageInterface
{
    /**
     * Message event
     *
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame): void;
}
