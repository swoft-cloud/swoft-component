<?php declare(strict_types=1);


namespace Swoft\Task;


use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Server\Server;
use Swoft\Task\Contract\RequestInterface;

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
     * @param Server $server
     * @param int    $taskId
     * @param int    $srcWorkerId
     * @param string $data
     *
     * @return Request
     * @throws \ReflectionException
     * @throws ContainerException
     */
    public static function new(Server $server, int $taskId, int $srcWorkerId, string $data): self
    {
        $instance = self::__instance();

        $instance->server      = $server;
        $instance->taskId      = $taskId;
        $instance->srcWorkerId = $srcWorkerId;
        $instance->data        = $data;

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
     * Clear
     */
    public function clear(): void
    {
        $this->server      = null;
        $this->taskId      = null;
        $this->srcWorkerId = null;
    }
}