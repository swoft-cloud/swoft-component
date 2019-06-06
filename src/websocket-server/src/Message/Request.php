<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\WebSocket\Server\Contract\RequestInterface;
use Swoole\WebSocket\Frame;

/**
 * Class Request
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Request implements RequestInterface
{
    use PrototypeTrait;

    /**
     * @var Frame
     */
    private $frame;

    /**
     * @param Frame $frame
     *
     * @return Request
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(Frame $frame): self
    {
        $self = self::__instance();

        // Init properties
        $self->frame = $frame;

        return $self;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->frame->fd;
    }

    /**
     * @return int
     */
    public function getOpcode(): int
    {
        return $this->frame->opcode;
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->frame;
    }
}
