<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Server;
use Swoole\Server as SwServer;

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
     * Default listen port
     *
     * @var int
     */
    protected $port = 18309;

    /**
     * @var string
     */
    protected $pidName = 'swoft-tcp';

    /**
     * @var string
     */
    protected $pidFile = '@runtime/swoft-tcp.pid';

    /**
     * @var string
     */
    protected $commandFile = '@runtime/swoft-tcp.command';

    /**
     * Start server
     *
     * @throws ServerException
     */
    public function start(): void
    {
        $this->swooleServer = new SwServer($this->host, $this->port, $this->mode, $this->type);

        // Start server
        $this->startSwoole();
    }

    /**
     * Disconnect the client connection, will trigger onClose
     *
     * @param int  $fd
     * @param bool $reset Whether force close connection
     *
     * @return bool
     */
    public function disconnect(int $fd, bool $reset = false): bool
    {
        // If it's invalid fd
        if (!$this->swooleServer->exist($fd)) {
            return false;
        }

        return $this->swooleServer->close($fd, $reset);
    }
}
