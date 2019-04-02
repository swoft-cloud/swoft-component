<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Server\Server;
use Swoole\Server as SwooleServer;
use Swoft\Server\Exception\ServerException;

class ServiceServer extends Server
{
    /**
     * Default port
     *
     * @var int
     */
    protected $port = 18307;

    /**
     * @var string
     */
    protected $pidName = 'swoft-rpc';

    /**
     * @var string
     */
    protected $pidFile = '@runtime/swoft-rpc.pid';

    /**
     * Start server
     *
     * @throws ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new SwooleServer($this->host, $this->port, $this->mode, $this->type);
        $this->startSwoole();
    }
}