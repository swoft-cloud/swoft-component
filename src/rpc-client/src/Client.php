<?php declare(strict_types=1);


namespace Swoft\Rpc\Client;


use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Rpc\Contract\PacketInterface;

/**
 * Class Client
 *
 * @since 2.0
 */
class Client
{
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
     * @var PacketInterface
     */
    protected $packet;

    protected $extender;

    protected $provider;

    /**
     * @param Pool $pool
     *
     * @return Connection
     * @throws Exception\RpcClientException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function createConnection(Pool $pool): Connection
    {
        $connection = Connection::new($this, $pool);
        $connection->create();

        return $connection;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @return PacketInterface
     * @throws RpcClientException
     */
    public function getPacket(): PacketInterface
    {
        if (empty($this->packet)) {
            throw new RpcClientException(
                sprintf('Client(%s) packet can not be null', __CLASS__)
            );
        }
        return $this->packet;
    }
}