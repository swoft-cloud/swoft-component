<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 13:48
 */

namespace Swoft\WebSocket\Server;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Context\AbstractContext;
use Swoft\Http\Message\ServerRequest;
use Swoole\Http\Request;

/**
 * Class Connection
 * @package Swoft\WebSocket\Server
 * @since 2.0
 * @Bean(scope=Bean::REQUEST)
 */
class Connection extends AbstractContext
{
    private const METADATA_KEY = 'metadata';

    /**
     * The map for coroutine id to fd
     * @var array
     * [ coID => fd ]
     */
    private static $map = [];

    /**
     * @var int
     */
    private $fd = 0;

    /**
     * @var ServerRequest|ServerRequestInterface
     */
    private $request;

    /**
     * @var bool
     */
    private $handshake = false;

    /**
     * bind FD and CID relationship. (should call it on handshake ok)
     * @param int $fd
     */
    public static function bindFd(int $fd): void
    {
        self::$map[Co::tid()] = $fd;
    }

    /**
     * unbind FD and CID relationship. (should call it on close OR error)
     * @return int
     */
    public static function unbindFd(): int
    {
        $fd  = 0;
        $tid = Co::tid();

        if (isset(self::$map[$tid])) {
            $fd = self::$map[$tid];
            unset(self::$map[$tid]);
        }

        return $fd;
    }

    /**
     * @return int
     */
    public static function getBoundedFd(): int
    {
        $tid = Co::tid();
        return self::$map[$tid] ?? 0;
    }

    /**
     * Initialize connection object
     * @param int     $fd
     * @param Request $request
     */
    public function initialize(int $fd, Request $request): void
    {
        $this->fd = $fd;

        $this->set(self::METADATA_KEY, $this->buildMetadata($fd, $request));

        $psr7Req = '';

        // ensure is false.
        $this->handshake = false;
    }

    /**
     * @param int     $fd
     * @param Request $request
     * @return array
     */
    protected function buildMetadata(int $fd, Request $request): array
    {
        $info = \server()->getClientInfo($fd);
        $path = \parse_url($request->server['request_uri'], \PHP_URL_PATH);

        \server()->log("onHandShake: Client #{$fd} send handshake request to {$path}, client info: ", $info, 'debug');

        return [
            'fd'            => $fd,
            'ip'            => $info['remote_ip'],
            'port'          => $info['remote_port'],
            'path'          => $path,
            'connectTime'   => $info['connect_time'],
            'handshakeTime' => \microtime(true),
        ];
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
     * @return ServerRequest
     */
    public function getRequest(): ServerRequest
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

}
