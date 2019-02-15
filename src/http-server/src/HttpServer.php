<?php declare(strict_types=1);

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
    protected static $serverType = 'HTTP';

    /**
     * Start server
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new \Swoole\Http\Server($this->host, $this->port, $this->mode, $this->type);
        $this->startSwoole();
    }
}