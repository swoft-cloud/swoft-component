<?php declare(strict_types=1);


namespace Swoft\Rpc\Client;


use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Rpc\Client\Contract\ConnectionInterface;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoole\Coroutine\Client;

/**
 * Class Connection
 *
 * @since 2.0
 */
class Connection extends AbstractConnection implements ConnectionInterface
{
    /**
     * @var Client
     */
    protected $connection;

    /**
     * Default host
     *
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * Default port
     *
     * @var int
     */
    protected $port = 18307;

    /**
     * Setting
     *
     * @var array
     */
    protected $setting = [];

    /**
     * @throws RpcClientException
     */
    public function create(): void
    {
        $connection = new Client(SWOOLE_SOCK_TCP);

        if (!empty($this->setting)) {
            $connection->set($this->setting);
        }

        if (!$connection->connect($this->host, $this->port)) {
            throw new RpcClientException(
                sprintf('Connect failed. host=%s port=%d', $this->host, $this->port)
            );
        }

        $this->connection = $connection;
    }

    public function reconnect(): bool
    {

    }

    /**
     * @param string $data
     *
     * @return bool
     */
    public function send(string $data): bool
    {
        return (bool)$this->connection->send($data);
    }

    /**
     * @return string
     */
    public function recv(): string
    {
        return $this->connection->recv();
    }

    public function getLastTime(): int
    {
        return time();
    }
}