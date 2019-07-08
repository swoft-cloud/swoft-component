<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoole\Server;

/**
 * Class Response
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response
{
    /**
     * Request fd
     *
     * @var int
     */
    private $reqFd = -1;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var bool
     */
    private $sent = false;

    /**
     * @param int $fd
     *
     * @return self
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new(int $fd = -1): self
    {
        /** @var self $self */
        $self = bean(self::class);

        // Set properties
        $self->reqFd = $fd;
        $self->sent  = false;

        return $self;
    }

    /**
     * @param Server|null $server
     *
     * @return int
     */
    public function send(Server $server = null): int
    {
        if ($this->sent) {
            return 0;
        }

        $server = $server ?: Swoft::server()->getSwooleServer();

        //$server->
    }

    /**
     * @return int
     */
    public function getReqFd(): int
    {
        return $this->reqFd;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->sent;
    }
}
