<?php

namespace Swoft\WebSocket\Server\Contract;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Interface RequestHandlerInterface
 * @package Swoft\WebSocket\Server\Contract
 */
interface RequestHandlerInterface extends ModuleInterface
{
    /**
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame): void;
}
