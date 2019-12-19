<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Session\Session;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\StorageInterface;
use Swoft\WebSocket\Server\Swoole\CloseListener;
use Swoft\WebSocket\Server\Swoole\HandshakeListener;
use Swoft\WebSocket\Server\Swoole\MessageListener;
use Swoole\Http\Request;
use function gethostname;
use function server;
use function sprintf;

/**
 * Class ConnectionStorage - use for restore connection data on worker reload
 *
 * @since 2.0.6
 * @deprecated please use ConnectionManager instead, will remove it on 2.0.10+
 * @Bean("wsConnStorage")
 */
class ConnectionStorage
{
    /**
     * @var bool
     */
    private $enable = false;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * Storage connection on handshake successful
     *
     * @param Request  $request
     *
     * @see HandshakeListener::onHandshake()
     *
     * You should call the method on event: {@see WsServerEvent::HANDSHAKE_SUCCESS}
     *
     */
    public function storage(Request $request): void
    {
        if (!$this->enable) {
            return;
        }

        $key  = self::genKey($fd = $request->fd);
        $conn = Session::mustGet($fd);

        $this->storage->set($key, $conn->toString());
    }

    /**
     * Restore connection on worker reload
     *
     * @param int $fd
     *
     * @return bool
     * @see MessageListener::onMessage()
     *
     * You should call the method on event: {@see WsServerEvent::MESSAGE_RECEIVE}
     *
     */
    public function restore(int $fd): bool
    {
        if (!$this->enable) {
            return false;
        }

        $key = self::genKey($fd);

        // If not exist
        if (!$json = $this->storage->get($key)) {
            return false;
        }

        // Restore connection object
        $data = JsonHelper::decode($json);
        $conn = Connection::newFromArray($data);

        // Bind connection and bind cid => sid(fd)
        Session::set((string)$fd, $conn);

        return true;
    }

    /**
     * Remove storage connection data on close connection
     *
     * @param int $fd
     *
     * @return bool
     * @see CloseListener::onClose()
     *
     * You should call the method on event: {@see WsServerEvent::CLOSE_BEFORE}
     *
     */
    public function remove(int $fd): bool
    {
        if (!$this->enable) {
            return false;
        }

        $key = self::genKey($fd);

        return $this->storage->del($key);
    }

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * @param int $fd
     *
     * @return string
     */
    public static function genKey(int $fd): string
    {
        $hostname = gethostname();
        $workerId = server()->getSwooleServer()->worker_id;

        return sprintf('ws%s:%d:%d', $hostname, $workerId, $fd);
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }
}
