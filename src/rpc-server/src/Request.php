<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Packet;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoole\Server;
use function microtime;
use function bean;

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
     * Router handler attribute
     */
    public const ROUTER_ATTRIBUTE = 'swoftRouterHandler';

    /**
     * @var string
     */
    protected $version = '';

    /**
     * @var string
     */
    protected $interface = '';

    /**
     * @var string
     */
    protected $method = '';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $ext = [];

    /**
     * Raw data
     *
     * @var string
     */
    protected $data = '';

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var int
     */
    protected $fd = 0;

    /**
     * @var int
     */
    protected $reactorId = 0;

    /**
     * @var float
     */
    protected $requestTime = 0;

    /**
     * @var array
     *
     * @example
     * [
     *    'key' => value,
     *    'key' => value,
     * ]
     */
    protected $attributes = [];

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     *
     * @return Request
     * @throws RpcException
     */
    public static function new(
        Server $server = null,
        int $fd = null,
        int $reactorId = null,
        string $data = null
    ): self {
        $instance = self::__instance();

        /* @var Packet $packet */
        $packet   = bean('rpcServerPacket');
        $protocol = $packet->decode($data);

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
     * @return array
     * @throws ReflectionException
     */
    public function getParamsMap(): array
    {
        $rc       = BeanFactory::getReflection($this->interface);
        $rxParams = $rc['methods'][$this->method]['params'];

        $paramsMap = [];
        foreach ($rxParams as $index => $methodParams) {
            if (!isset($this->params[$index])) {
                break;
            }

            [$name] = $methodParams;
            $paramsMap[$name] = $this->params[$index];
        }
        return $paramsMap;
    }

    /**
     * @param array $params
     *
     * @return RequestInterface
     */
    public function withParams(array $params): RequestInterface
    {
        $clone = clone $this;

        $clone->params = $params;
        return $clone;
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

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function getAttribute(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }
}
