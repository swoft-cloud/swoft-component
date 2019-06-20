<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Server;
use function array_merge;

/**
 * Class TcpServer
 *
 * @Bean("tcpServer")
 *
 * @since 2.0
 */
class TcpServer extends Server
{
    /**
     * Start server
     *
     * @throws ContainerException
     * @throws ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new \Swoole\Server($this->host, $this->port, $this->mode, $this->type);

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
