<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;

/**
 * Class Request
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Request
{
    /**
     * Receiver fd
     *
     * @var int
     */
    private $fd = -1;

    /**
     * @var string
     */
    private $rawData = '';

    /**
     * @var int
     */
    private $reactorId = -1;

    /**
     * @param int    $fd
     * @param string $data
     * @param int    $reactorId
     *
     * @return self
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new(int $fd, string $data, int $reactorId): self
    {
        /** @var self $self */
        $self = bean(self::class);

        // Set properties
        $self->fd      = $fd;
        $self->rawData = $data;
        $self->reactorId = $reactorId;

        return $self;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @param int $fd
     */
    public function setFd(int $fd): void
    {
        $this->fd = $fd;
    }

    /**
     * @return string
     */
    public function getRawData(): string
    {
        return $this->rawData;
    }
}
