<?php

namespace Swoft\WebSocket\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Server;

/**
 * Class WebscoketServer
 *
 * @Bean("wsServer")
 *
 * @since 2.0
 */
class WebscoketServer extends Server
{
    /**
     * Start
     */
    public function start(): void
    {
        $this->swooleServer = new \Co\Websocket\Server($this->host, $this->port);
        $this->startSwoole();
    }
}