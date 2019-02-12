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
use Swoft\Context\AbstractContext;
use Swoft\Http\Message\ServerRequest;
use Swoole\Http\Request;

/**
 * Class Connection
 * @package Swoft\WebSocket\Server
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Connection extends AbstractContext implements ConnectionInterface
{
    private const METADATA_KEY = 'metadata';

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
