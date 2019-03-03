<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Session\SessionInterface;
use Swoft\Concern\DataPropertyTrait;
use Swoft\Http\Message\Request;
use Swoole\Http\Request as SwooleRequest;

/**
 * Class Connection
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Connection implements SessionInterface
{
    use DataPropertyTrait;

    private const METADATA_KEY = 'metadata';

    /**
     * @var int
     */
    private $fd = 0;

    /**
     * @var Request|ServerRequestInterface
     */
    private $request;

    /**
     * @var bool
     */
    private $handshake = false;

    /**
     * Initialize connection object
     *
     * @param int           $fd
     * @param SwooleRequest $request
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function initialize(int $fd, SwooleRequest $request): void
    {
        $this->fd = $fd;

        $this->set(self::METADATA_KEY, $this->buildMetadata($fd, $request));

        // ensure is false.
        $this->handshake = false;
    }

    /**
     * @param int           $fd
     * @param SwooleRequest $request
     *
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function buildMetadata(int $fd, SwooleRequest $request): array
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
     * Clear resource
     */
    public function clear(): void
    {
        $this->data = [];
        // clear
        $this->request   = null;
        $this->handshake = false;
    }
}
