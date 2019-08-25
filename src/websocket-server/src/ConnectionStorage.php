<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request as Psr7Request;
use Swoft\Http\Message\Response as Psr7Response;
use Swoft\Session\Session;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\StorageInterface;
use Swoft\WebSocket\Server\Swoole\CloseListener;
use Swoft\WebSocket\Server\Swoole\HandshakeListener;
use Swoft\WebSocket\Server\Swoole\MessageListener;
use Swoole\Http\Request;
use Swoole\Http\Response;
use function gethostname;
use function server;
use function sprintf;

/**
 * Class ConnectionStorage - use for restore connection data on worker reload
 *
 * @since 2.0.6
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
     * @see HandshakeListener::onHandshake()
     *
     * You should call the method on event: {@see WsServerEvent::HANDSHAKE_SUCCESS}
     *
     * @param Request  $request
     * @param Response $response
     */
    public function storage(Request $request, Response $response): void
    {
        if (!$this->enable) {
            return;
        }

        $key  = self::genKey($fd = $request->fd);
        $conn = Session::mustGet($fd);

        $this->storage->set($key, JsonHelper::encode([
            // request data
            'fd'        => $fd,
            'get'       => $request->get,
            'post'      => $request->post,
            'cookie'    => $request->cookie,
            'header'    => $request->header,
            'server'    => $request->server,
            // response data
            'resHeader' => $response->header,
            'resCookie' => $response->cookie,
            // module info
            'moduleInfo' => $conn->getModuleInfo()
        ]));
    }

    /**
     * Restore connection on worker reload
     * @see MessageListener::onMessage()
     *
     * You should call the method on event: {@see WsServerEvent::MESSAGE_RECEIVE}
     *
     * @param int $fd
     *
     * @return bool
     */
    public function restore(int $fd): bool
    {
        if (!$this->enable) {
            return false;
        }

        $key = self::genKey($fd);

        // if not exist
        if (!$json = $this->storage->get($key)) {
            return false;
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

        // Initialize psr7 Request and Response
        $psr7Req  = Psr7Request::new($req);
        $psr7Res  = Psr7Response::new($res);
        $wsServer = Swoft::getBean('wsServer');

        // Restore connection object
        $conn = Connection::new($wsServer, $psr7Req, $psr7Res);
        $conn->setHandshake(true);
        $conn->setModuleInfo($data['moduleInfo']);

        // Bind connection and bind cid => sid(fd)
        Session::set((string)$fd, $conn);

        return true;
    }

    /**
     * Remove storage connection data on close connection
     * @see CloseListener::onClose()
     *
     * You should call the method on event: {@see WsServerEvent::CLOSE_BEFORE}
     *
     * @param int $fd
     *
     * @return bool
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
