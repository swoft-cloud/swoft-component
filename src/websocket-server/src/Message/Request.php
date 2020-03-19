<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Message;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Stdlib\Concern\DataPropertyTrait;
use Swoft\WebSocket\Server\Contract\RequestInterface;
use Swoole\WebSocket\Frame;
use Swoft\WebSocket\Server\WsServerBean;

/**
 * Class Request
 *
 * @since 2.0
 * @Bean(name=WsServerBean::REQUEST, scope=Bean::PROTOTYPE)
 */
class Request implements RequestInterface
{
    use DataPropertyTrait;

    /**
     * The request data key for storage matched route info.
     */
    public const ROUTE_INFO   = '__route_info';

    /**
     * @var Frame
     */
    private $frame;

    /**
     * @var Message
     */
    private $message;

    /**
     * @param Frame $frame
     *
     * @return Request
     */
    public static function new(Frame $frame): self
    {
        $self = Swoft::getBean(WsServerBean::REQUEST);

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
     * @return mixed
     */
    public function getRawData()
    {
        return $this->frame->data;
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->frame;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }
}
