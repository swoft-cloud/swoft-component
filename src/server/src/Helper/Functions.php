<?php

if (!function_exists('server')) {
    /**
     * Get server instance
     *
     * @return \Swoft\Server\Server|\Swoft\Http\Server\HttpServer|\Swoft\WebSocket\Server\WebSocketServer
     */
    function server(): \Swoft\Server\Server
    {
        return \Swoft\Server\Server::getServer();
    }
}
