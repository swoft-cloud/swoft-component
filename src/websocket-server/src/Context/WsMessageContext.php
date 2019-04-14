<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Context\AbstractContext;
use Swoole\WebSocket\Frame;

/**
 * Class WsMessageContext - on ws message event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsMessageContext extends AbstractContext
{
    /**
     * @var Frame
     */
    private $frame;

    /**
     * @param Frame $frame
     * @return WsMessageContext
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Frame $frame): self
    {
        /** @var self $ctx */
        $ctx = BeanFactory::getPrototype(__CLASS__);

        // Initial properties
        $ctx->frame = $frame;

        return $ctx;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->frame->fd;
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->frame;
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        parent::clear();

        $this->frame = null;
    }
}
