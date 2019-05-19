<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\AbstractContext;

/**
 * Class WsCloseContext - on ws close event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsCloseContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var int
     */
    private $fd;

    /**
     * @var int
     */
    private $reactorId;

    /**
     * @param int $fd
     * @param int $reactorId
     *
     * @return WsCloseContext
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public static function new(int $fd, int $reactorId): self
    {
        /** @var self $ctx */
        $ctx = self::__instance();

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
