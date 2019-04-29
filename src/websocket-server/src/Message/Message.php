<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoole\WebSocket\Frame;

/**
 * Class Message
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Message
{
    use PrototypeTrait;

    /**
     * @var string
     */
    private $cmd;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param Frame $frame
     *
     * @return Message
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Frame $frame): self
    {
        /** @var self $self */
        $self = self::__instance();

        return $self;
    }

    /**
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmd;
    }

    /**
     * @param string $cmd
     */
    public function setCmd(string $cmd): void
    {
        $this->cmd = $cmd;
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
}
