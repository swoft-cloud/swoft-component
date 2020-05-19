<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Exception\ServerException;
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
     * Server type
     *
     * @var string
     */
    protected static $serverType = 'HTTP';

    /**
     * Default http port
     *
     * @var int
     */
    protected $port = 18306;

    /**
     * @var string
     */
    protected $pidName = 'swoft-http';

    /**
     * Start server
     *
     * @throws ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new \Swoole\Http\Server($this->host, $this->port, $this->mode, $this->type);
        $this->startSwoole();
    }
}
