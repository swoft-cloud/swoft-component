<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Concern\DataPropertyTrait;
use Swoft\Context\ContextInterface;
use Swoole\WebSocket\Frame;

/**
 * Class WsMessageContext
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsMessageContext implements ContextInterface
{
    use DataPropertyTrait;

    /**
     * @var Frame
     */
    private $frame;

    /**
     * @param Frame $frame
     */
    public function initialize(Frame $frame): void
    {
        $this->frame = $frame;
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
        $this->data  = [];
        $this->frame = null;
    }
}
