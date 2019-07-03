<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Context;

use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\AbstractContext;

/**
 * Class TcpReceiveContext
 *
 * @since 2.0.3
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TcpReceiveContext extends AbstractContext
{
    /**
     * @var int
     */
    private $fd;

    /**
     * @var string
     */
    private $rawData;

    /**
     * @var int
     */
    private $reactorId;

    /**
     * @param int    $fd
     * @param int    $reactorId
     *
     * @param string $data
     *
     * @return self
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new(int $fd, int $reactorId, string $data): self
    {
        /** @var self $ctx */
        $ctx = Swoft::getBean(self::class);

        // Initial properties
        $ctx->fd        = $fd;
        $ctx->rawData    = $data;
        $ctx->reactorId = $reactorId;

        return $ctx;
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
     * @return string
     */
    public function getRawData(): string
    {
        return $this->rawData;
    }
}
