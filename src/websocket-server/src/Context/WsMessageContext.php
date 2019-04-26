<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoole\WebSocket\Frame;

/**
 * Class WsMessageContext - on ws message event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsMessageContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var Frame
     */
    private $frame;

    /**
     * @var MessageParserInterface
     */
    private $parser;

    /**
     * @param Frame $frame
     *
     * @return WsMessageContext
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Frame $frame): self
    {
        /** @var self $ctx */
        $ctx = self::__instance();


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
        $this->parser = null;
    }

    /**
     * @return MessageParserInterface
     */
    public function getParser(): MessageParserInterface
    {
        return $this->parser;
    }

    /**
     * @param MessageParserInterface $parser
     */
    public function setParser(MessageParserInterface $parser): void
    {
        $this->parser = $parser;
    }
}
