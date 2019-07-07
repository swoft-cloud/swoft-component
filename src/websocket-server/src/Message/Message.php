<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use JsonSerializable;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\JsonHelper;
use function bean;
use function is_scalar;

/**
 * Class Message
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Message implements JsonSerializable
{
    use PrototypeTrait;

    /**
     * Message command. it's must exists. eg: 'home.index'
     *
     * @var string
     */
    private $cmd;

    /**
     * Message data
     *
     * @var mixed
     */
    private $data;

    /**
     * Message extra data
     *
     * @var mixed
     */
    private $ext;

    /**
     * @param string $cmd
     * @param mixed  $data
     *
     * @param array  $ext
     *
     * @return Message
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new(string $cmd, $data, array $ext = []): self
    {
        /** @var self $self */
        $self = bean(self::class);

        // Set properties
        $self->cmd  = $cmd;
        $self->data = $data;
        $self->ext  = $ext;

        return $self;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'cmd'  => $this->cmd,
            'data' => $this->data,
            'ext'  => $this->ext,
        ];
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if (is_scalar($this->data)) {
            return (string)$this->data;
        }

        return JsonHelper::encode($this->data);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
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

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param mixed $ext
     */
    public function setExt($ext): void
    {
        $this->ext = $ext;
    }
}
