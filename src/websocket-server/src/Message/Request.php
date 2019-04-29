<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\WebSocket\Server\Contract\ResponseInterface;
use Swoole\WebSocket\Frame;

/**
 * Class Request
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Request implements ResponseInterface
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
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Frame $frame): self
    {
        $self = self::__instance();

        $self->frame = $frame;

        return $self;
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->frame;
    }
}
