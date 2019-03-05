<?php

namespace Swoft\Server\Swoole;


use Co\Websocket\Server;
use Swoft\Http\Message\Request;

/**
 * Interface OpenInterface
 *
 * @since 2.0
 */
interface OpenInterface
{
    /**
     * Open event
     *
     * @param Server  $server
     * @param Request $request
     */
    public function onOpen(Server $server, Request $request): void;
}
