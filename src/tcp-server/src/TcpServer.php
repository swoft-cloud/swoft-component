<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Server;
use Swoole\Server as SwServer;
use function array_merge;

/**
 * Class TcpServer
 *
 * @since 2.0
 * @Bean("tcpServer")
 */
class TcpServer extends Server
{
    // protected static $serverType = 'TCP';

    /**
     * @var string
     */
    protected $pidFile = '@runtime/swoft-ws.pid';

    /**
     * @var string
     */
    protected $commandFile = '@runtime/swoft-ws.command';

    /**
     * Start server
     *
     * @throws ContainerException
     * @throws ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new SwServer($this->host, $this->port, $this->mode, $this->type);

        // Start server
        $this->startSwoole();
    }

    /**
     * @return array
     */
    public function defaultSetting(): array
    {
        return array_merge(parent::defaultSetting(), [
            'open_eof_check' => true,
            'package_eof'    => "\r\n\r\n",
        ]);
    }
}
