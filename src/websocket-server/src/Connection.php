<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server;

use Psr\Http\Message\ServerRequestInterface;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\SessionInterface;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Stdlib\Concern\DataPropertyTrait;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoft\WebSocket\Server\MessageParser\RawTextParser;
use Swoft\WebSocket\Server\Router\Router;
use function microtime;
use function server;
use const WEBSOCKET_OPCODE_TEXT;

/**
 * Class Connection
 *
 * @since 2.0
 * @Bean(name=WsServerBean::CONNECTION, scope=Bean::PROTOTYPE)
 */
class Connection implements SessionInterface
{
    use DataPropertyTrait;

    private const METADATA_KEY = '_metadata';

    /**
     * @var int
     */
    private $fd = 0;

    /**
     * @var string
     */
    // private $sessionId = '';

    /**
     * Save handshake success module instance
     *
     * @var WsModuleInterface
     */
    // private $module;

    /**
     * @var WebSocketServer
     */
    private $server;

    /**
     * @var Request|ServerRequestInterface
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var bool
     */
    private $handshake = false;

    /**
     * @var array
     * @see Router::$modules for fileds information
     */
    private $moduleInfo = [];

    /**
     * @return ConnectionManager
     */
    public static function manager(): ConnectionManager
    {
        return Swoft::getBean(WsServerBean::MANAGER);
    }

    /**
     * @return static
     */
    public static function current(): self
    {
        /** @see ConnectionManager::current() */
        return Swoft::getBean(WsServerBean::MANAGER)->current();
    }

    /**
     * @param WebSocketServer $server
     * @param Request         $request
     * @param Response        $response
     *
     * @return Connection
     */
    public static function new(WebSocketServer $server, Request $request, Response $response): self
    {
        /** @var self $sess */
        $sess = Swoft::getBean(WsServerBean::CONNECTION);

        $sess->fd = $fd = $request->getFd();

        $sess->server = $server;

        // Init meta info
        $sess->buildMetadata($fd, $request->getUriPath());

        $sess->request   = $request;
        $sess->response  = $response;
        $sess->handshake = false;

        return $sess;
    }

    /**
     * Restore connection object
     *
     * @param array $data
     *
     * @return static
     */
    public static function newFromArray(array $data): SessionInterface
    {
        // New request and response
        $req = new \Swoole\Http\Request();
        $res = new \Swoole\Http\Response();

        // Initialize swoole request
        $req->fd     = (int)$data['fd'];
        $req->get    = $data['get'];
        $req->post   = $data['post'];
        $req->cookie = $data['cookie'];
        $req->header = $data['header'];
        $req->server = $data['server'];

        // Initialize swoole response
        $res->cookie = $data['resCookie'];
        $res->header = $data['resHeader'];

        // Initialize psr7 Request and Response
        $psr7Req  = Request::new($req);
        $psr7Res  = Response::new($res);
        $wsServer = Swoft::getBean(WsServerBean::SERVER);

        // Initialize connection
        $conn = self::new($wsServer, $psr7Req, $psr7Res);
        // Session data
        $conn->data = $data['sessionData'];
        $conn->setHandshake(true);
        $conn->setModuleInfo($data['moduleInfo']);

        return $conn;
    }

    /**
     * @param int    $fd
     * @param string $path
     */
    private function buildMetadata(int $fd, string $path): void
    {
        $info = $this->server->getClientInfo($fd);

        server()->log("Handshake: conn#{$fd} session data created. path: {$path}, client info: ", $info, 'debug');

        $this->set(self::METADATA_KEY, [
            'fd'            => $fd,
            'ip'            => $info['remote_ip'],
            'port'          => $info['remote_port'],
            'path'          => $path,
            'connectTime'   => $info['connect_time'],
            'handshakeTime' => microtime(true),
        ]);
    }

    /**
     * @param string   $data
     * @param int      $opcode
     * @param bool|int $finish
     *
     * @return bool
     */
    public function push(string $data, int $opcode = WEBSOCKET_OPCODE_TEXT, $finish = 1): bool
    {
        return $this->server->push($this->fd, $data, $opcode, $finish);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $request  = $this->request->getCoRequest();
        $response = $this->response->getCoResponse();

        return [
            // request data
            'fd'          => $this->fd,
            'get'         => $request->get,
            'post'        => $request->post,
            'cookie'      => $request->cookie,
            'header'      => $request->header,
            'server'      => $request->server,
            // response data
            'resHeader'   => $response->header,
            'resCookie'   => $response->cookie,
            // module info
            'moduleInfo'  => $this->moduleInfo,
            // session data
            'sessionData' => $this->data,
        ];
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return JsonHelper::encode($this->toArray());
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        $this->data = [];
        // Clear data
        $this->request    = null;
        $this->response   = null;
        $this->handshake  = false;
        $this->moduleInfo = [];
    }

    /**
     * @return bool
     */
    public function isHandshake(): bool
    {
        return $this->handshake;
    }

    /**
     * @param bool $ok
     */
    public function setHandshake(bool $ok): void
    {
        $this->handshake = $ok;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->get(self::METADATA_KEY, []);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getMetaValue(string $key)
    {
        $data = $this->get(self::METADATA_KEY, []);

        return $data[$key] ?? null;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return MessageParserInterface
     */
    public function getParser(): MessageParserInterface
    {
        $parseClass = $this->getParserClass();

        return Swoft::getSingleton($parseClass);
    }

    /**
     * @return string
     */
    public function getParserClass(): string
    {
        return $this->moduleInfo['messageParser'] ?? RawTextParser::class;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getModuleInfo(): array
    {
        return $this->moduleInfo;
    }

    /**
     * @param array $moduleInfo
     */
    public function setModuleInfo(array $moduleInfo): void
    {
        $this->moduleInfo = $moduleInfo;
    }

    /**
     * @return WebSocketServer
     */
    public function getServer(): WebSocketServer
    {
        return $this->server;
    }
}
