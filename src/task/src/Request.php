<?php declare(strict_types=1);


namespace Swoft\Task;


use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Task\Contract\RequestInterface;
use Swoft\Task\Exception\TaskException;
use Swoole\Server;

class Request implements RequestInterface
{
    use PrototypeTrait;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var int
     */
    private $taskId;

    /**
     * @var int
     */
    private $srcWorkerId;

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $name = '';

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
     * @param Server $server
     * @param int    $taskId
     * @param int    $srcWorkerId
     * @param string $data
     *
     * @return Request
     * @throws \ReflectionException
     * @throws ContainerException
     * @throws TaskException
     */
    public static function new(Server $server, int $taskId, int $srcWorkerId, string $data): self
    {
        $instance = self::__instance();

        $instance->server      = $server;
        $instance->taskId      = $taskId;
        $instance->srcWorkerId = $srcWorkerId;
        $instance->data        = $data;

        [
            $instance->type,
            $instance->name,
            $instance->method,
            $instance->params,
            $instance->ext
        ] = Packet::unpack($data);

        return $instance;
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
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    /**
     * @return int
     */
    public function getSrcWorkerId(): int
    {
        return $this->srcWorkerId;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * @param string $name
     * @param null   $default
     *
     * @return mixed|null
     */
    public function getExtKey(string $name, $default = null)
    {
        return $this->ext[$name] ?? $default;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Clear
     */
    public function clear(): void
    {
        $this->server      = null;
        $this->taskId      = null;
        $this->srcWorkerId = null;
        $this->params      = [];
        $this->ext         = [];
        $this->data        = [];
    }
}