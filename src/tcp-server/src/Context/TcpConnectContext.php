<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Context;

use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\AbstractSimpleContext;

/**
 * Class TcpConnectContext
 *
 * @since 2.0.3
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TcpConnectContext extends AbstractSimpleContext
{
    /**
     * @var int
     */
    protected $fd;

    /**
     * @var int
     */
    protected $reactorId;

    /**
     * @param int    $fd
     * @param int    $reactorId
     *
     * @return self
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(int $fd, int $reactorId): self
    {
        /** @var self $ctx */
        $ctx = Swoft::getBean(self::class);

        // Initial properties
        $ctx->fd        = $fd;
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
}
