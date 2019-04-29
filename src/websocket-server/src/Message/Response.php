<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\WebSocket\Server\Contract\ResponseInterface;
use Swoft\WebSocket\Server\WebSocketServer;

/**
 * Class Response
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response implements ResponseInterface
{
    use PrototypeTrait;

    /**
     * @var int
     */
    private $sender;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param int $sender
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(int $sender = -1): self
    {
        $self = self::__instance();

        $self->sender = $sender;

        return $self;
    }

    /**
     * @param int $sender Sender fd
     * @return $this
     */
    public function from(int $sender): self
    {
        return $this->setSender($sender);
    }

    public function send(): void
    {

    }

    /**
     * @return int
     */
    public function getSender(): int
    {
        return $this->sender;
    }

    /**
     * @param int $sender
     *
     * @return Response
     */
    public function setSender(int $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return WebSocketServer
     */
    public function wsServer(): WebSocketServer
    {
        return WebSocketServer::getServer();
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
     *
     * @return Response
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }
}
