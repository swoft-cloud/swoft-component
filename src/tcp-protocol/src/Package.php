<?php declare(strict_types=1);

namespace Swoft\Tcp\Protocol;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\JsonHelper;
use function bean;

/**
 * Class Package Structure
 *
 * @since 2.0.3
 */
class Package
{
    /**
     * Message request command. it's must exists.
     *
     * @var string
     */
    private $cmd = '';

    /**
     * Message body data
     *
     * @var mixed
     */
    private $data;

    /**
     * Message extra data
     *
     * @var array
     */
    private $ext = [];

    /**
     * @param string $route
     * @param        $data
     * @param array  $ext
     *
     * @return Package
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(string $route, $data, array $ext = []): self
    {
        /** @var self $self */
        $self = bean(self::class);

        // Set properties
        $self->cmd  = $route;
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
    public function getDataString(): string
    {
        if (is_scalar($this->data)) {
            return (string)$this->data;
        }

        return JsonHelper::encode($this->data);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return JsonHelper::encode($this->toArray());
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
     *
     * @return Package
     */
    public function setCmd(string $cmd): Package
    {
        $this->cmd = $cmd;
        return $this;
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
     * @return array
     */
    public function getExt(): array
    {
        return $this->ext;
    }

    /**
     * @param array $ext
     */
    public function setExt(array $ext): void
    {
        $this->ext = $ext;
    }
}
