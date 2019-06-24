<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use JsonSerializable;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\JsonHelper;
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
     * Message command. it's must exists.
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
     * @return Message
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(string $cmd, $data): self
    {
        /** @var self $self */
        $self = self::__instance();

        // Set properties
        $self->cmd  = $cmd;
        $self->data = $data;

        return $self;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $ret = [
            'cmd'  => $this->cmd,
            'data' => $this->data,
        ];

        if ($this->ext !== null) {
            $ret['ext'] = $this->ext;
        }

        return $ret;
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
