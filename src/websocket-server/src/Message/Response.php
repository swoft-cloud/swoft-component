<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Server\Concern\CommonProtocolDataTrait;
use Swoft\WebSocket\Server\Contract\ResponseInterface;
use function bean;
use const WEBSOCKET_OPCODE_TEXT;

/**
 * Class Response
 *
 * @since 2.0.1
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response implements ResponseInterface
{
    use CommonProtocolDataTrait;

    /**
     * Receiver fd
     *
     * @var int
     */
    private $fd = -1;

    /**
     * @var string
     */
    private $content = '';

    /**
     * Receiver fd list
     *
     * @var array
     */
    private $fds = [];

    /**
     * Sender fd
     *
     * @var int
     */
    private $sender = -1;

    /**
     * @var bool
     */
    private $sendToAll = false;

    /**
     * @var bool
     */
    private $sent = false;

    /**
     * @var bool
     */
    private $finish = true;

    /**
     * @var int WebSocket opcode value
     *          text:   WEBSOCKET_OPCODE_TEXT   = 1
     *          binary: WEBSOCKET_OPCODE_BINARY = 2
     *          close:  WEBSOCKET_OPCODE_CLOSE  = 8
     *          ping:   WEBSOCKET_OPCODE_PING   = 9
     *          pong:   WEBSOCKET_OPCODE_PONG   = 10
     */
    private $opcode = WEBSOCKET_OPCODE_TEXT;

    /**
     * @param int $sender
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(int $sender = -1): self
    {
        $self = bean(self::class);

        // Set properties
        $self->sent      = false;
        $self->sender    = $sender;
        $self->sendToAll = false;

        return $self;
    }

    /**
     * @param int $sender Sender fd
     *
     * @return $this
     */
    public function from(int $sender): ResponseInterface
    {
        return $this->setSender($sender);
    }

    /**
     * @param int $fd
     *
     * @return Response
     */
    public function to(int $fd): ResponseInterface
    {
        return $this->setFd($fd);
    }

    /**
     * @param int $fd
     *
     * @return Response
     */
    public function toOne(int $fd): self
    {
        return $this->setFd($fd);
    }

    /**
     * @param int[] $fd
     *
     * @return Response
     */
    public function toMore(array $fd): ResponseInterface
    {
        return $this->toSome($fd);
    }

    /**
     * @param int[] $fds
     *
     * @return Response
     */
    public function toSome(array $fds): ResponseInterface
    {
        $this->fds = $fds;
        return $this;
    }

    /**
     * @param bool $yes
     *
     * @return Response
     */
    public function toAll(bool $yes = true): ResponseInterface
    {
        $this->sendToAll = $yes;
        return $this;
    }

    /**
     * @return int
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function send(): int
    {
        if ($this->sent) {
            return 0;
        }

        $server = bean('wsServer');

        // Content for response
        $content = $this->content;
        if ($content === '') {
            $parser  = Context::mustGet()->getParser();
            $content = $parser->encode(Message::new('', $this->data));
        }

        // To all
        if ($this->sendToAll) {
            return $server->sendToAll($content, $this->sender);
        }

        // To some
        if ($this->fds) {
            return $server->sendToSome($content, $this->fds, [], $this->sender);
        }

        // To one
        $ok = $server->sendTo($this->fd, $content, $this->sender, $this->opcode, $this->finish);

        return $ok ? 1 : 0;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @param int $fd
     *
     * @return self
     */
    public function setFd(int $fd): ResponseInterface
    {
        $this->fd = $fd;
        return $this;
    }

    /**
     * @return int
     */
    public function getOpcode(): int
    {
        return $this->opcode;
    }

    /**
     * @param int $opcode
     *
     * @return self
     */
    public function setOpcode(int $opcode): ResponseInterface
    {
        $this->opcode = $opcode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFinish(): bool
    {
        return $this->finish;
    }

    /**
     * @param bool $finish
     *
     * @return self
     */
    public function setFinish(bool $finish): ResponseInterface
    {
        $this->finish = $finish;
        return $this;
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
    public function setSender(int $sender): ResponseInterface
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Response|ResponseInterface
     */
    public function setContent(string $content): ResponseInterface
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return ResponseInterface
     */
    public function setData($data): ResponseInterface
    {
        $this->data = $data;
        return $this;
    }
}
