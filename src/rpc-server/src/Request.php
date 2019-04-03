<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Rpc\Packet;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoole\Server;

/**
 * Class Request
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Request implements RequestInterface
{
    use PrototypeTrait;

    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $interface = '';

    /**
     * @var string
     */
    private $method = '';

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $ext = [];

    /**
     * Raw data
     *
     * @var string
     */
    private $data = '';

    /**
     * @var Server
     */
    private $server;

    /**
     * @var int
     */
    private $fd = 0;

    /**
     * @var int
     */
    private $reactorId = 0;

    /**
     * @var float
     */
    private $requestTime = 0;

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     *
     * @return Request
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public static function new(Server $server, int $fd, int $reactorId, string $data): Request
    {
        $instance = self::__instance();

        /* @var Packet $packet */
        $packet   = \bean('rpcServerPacket');
        $protocol = $packet->getPacket()->decode($data);

        $instance->version     = $protocol->getVersion();
        $instance->interface   = $protocol->getInterface();
        $instance->method      = $protocol->getMethod();
        $instance->params      = $protocol->getParams();
        $instance->ext         = $protocol->getExt();
        $instance->data        = $data;
        $instance->server      = $server;
        $instance->reactorId   = $reactorId;
        $instance->fd          = $fd;
        $instance->requestTime = microtime(true);

        return $instance;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getExt(): array
    {
        return $this->ext;
    }

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getExtKey(string $key, $default = null)
    {
        return $this->ext[$key] ?? $default;
    }

    /**
     * @param int        $index
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getParam(int $index, $default = null)
    {
        return $this->params[$index] ?? $default;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return int
     */
    public function getReactorId(): int
    {
        return $this->reactorId;
    }

    /**
     * @return float
     */
    public function getRequestTime(): float
    {
        return $this->requestTime;
    }
}