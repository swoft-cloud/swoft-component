<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\StorageInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;
use function gethostname;
use function server;
use function sprintf;

/**
 * Class ConnectionKeepLive
 *
 * @since 2.0.6
 */
class ConnectionKeepLive
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * should call on event: {@see WsServerEvent::HANDSHAKE_BEFORE}
     *
     * @param Request  $request
     * @param Response $response
     */
    public function storage(Request $request, Response $response): void
    {
        $key = self::genKey($request->fd);

        $this->storage->set($key, JsonHelper::encode([
            'fd'           => $request->fd,
            'serverParams' => $request->server,
        ]));
    }

    /**
     * should call on event: {@see WsServerEvent::MESSAGE_RECEIVE}
     *
     * @param int $fd
     *
     * @return Request
     */
    public function restore(int $fd): ?Request
    {
        $key = self::genKey($fd);

        // if not exist
        if (!$str = $this->storage->get($key)) {
            return null;
        }

        $req = new Request();

        // TODO
        $data = JsonHelper::decode($str);

        return $req;
    }

    /**
     * should call on event: {@see WsServerEvent::CLOSE_BEFORE}
     *
     * @param int $fd
     *
     * @return bool
     */
    public function remove(int $fd): bool
    {
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
}
