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
 * Class ConnectionStorage - use for restore connection data on worker reload
 *
 * @since 2.0.6
 */
class ConnectionStorage
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
            // request
            'fd'        => $request->fd,
            'get'       => $request->get,
            'post'      => $request->post,
            'cookie'    => $request->cookie,
            'header'    => $request->header,
            'server'    => $request->server,
            // response data
            'resHeader' => $response->header,
            'resCookie' => $response->cookie,
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
        if (!$json = $this->storage->get($key)) {
            return null;
        }

        $data = JsonHelper::decode($json);

        // New request and response
        $req = new Request();
        $res = new Response();

        // Init request
        $req->fd     = $fd;
        $req->get    = $data['get'];
        $req->post   = $data['post'];
        $req->cookie = $data['cookie'];
        $req->header = $data['header'];
        $req->server = $data['server'];

        // Init response
        $res->cookie = $data['resCookie'];
        $res->header = $data['resHeader'];

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
