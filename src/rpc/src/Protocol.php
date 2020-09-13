<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     * Default version
     */
    public const DEFAULT_VERSION = '1.0';

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
     * @var string
     */
    private $version = self::DEFAULT_VERSION;

    /**
     * Replace constructor
     *
     * @param string $version
     * @param string $interface
     * @param string $method
     * @param array  $params
     * @param array  $ext
     *
     * @return Protocol
     */
    public static function new(string $version, string $interface, string $method, array $params, array $ext)
    {
        $instance = self::__instance();

        $instance->version   = $version;
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
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getExt(): array
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
