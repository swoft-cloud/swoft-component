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
    /**
     * Default http port
     *
     * @var int
     */
    protected $port = 18306;

    /**
     * Server type
     *
     * @var string
     */
    protected static $serverType = 'HTTP';

    /**
     * Start server
     *
     * @throws \Swoft\Server\Exception\ServerException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function start(): void
    {
        $this->swooleServer = new \Swoole\Http\Server($this->host, $this->port, $this->mode, $this->type);
        $this->startSwoole();
    }
}
