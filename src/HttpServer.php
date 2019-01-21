<?php

namespace Swoft\Http\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Server;

/**
 * Class HttpServer
 *
 * @Bean("httpServer")
 *
 * @since 2.0
 */
class HttpServer extends Server
{
    /**
     * Start server
     */
    public function start(): void
    {
        $this->swooleServer = new \Co\Http\Server($this->host, $this->port, $this->mode, $this->type);
        $this->startSwoole();
    }
}