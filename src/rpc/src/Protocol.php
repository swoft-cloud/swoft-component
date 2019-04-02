<?php declare(strict_types=1);


namespace Swoft\Rpc;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;


/**
 * Class Protocol
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Protocol
{
    use PrototypeTrait;

    /**
     * @var string
     */
    private $interface = '';

    /**
     * @var string
     */
    private $method = '';

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $ext = [];

    /**
     * Replace constructor
     *
     * @param string $interface
     * @param string $method
     * @param array  $params
     * @param array  $ext
     *
     * @return Protocol
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(string $interface, string $method, array $params, array $ext)
    {
        $instance = self::__instance();

        $instance->interface = $interface;
        $instance->method    = $method;
        $instance->params    = $params;
        $instance->ext       = $ext;

        return $instance;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @param string $interface
     */
    public function setInterface(string $interface): void
    {
        $this->interface = $interface;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
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