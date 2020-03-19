<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Concern\CommonProtocolDataTrait;
use Swoft\Stdlib\Helper\JsonHelper;
use function bean;

/**
 * Class Package - Request package structure
 *
 * @since 2.0.3
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Package
{
    use CommonProtocolDataTrait;

    /**
     * Message request command. it's must exists.
     *
     * @var string
     */
    private $cmd = '';

    /**
     * @param string $route
     * @param        $data
     * @param array  $ext
     *
     * @return Package
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
     * Quick create new package from an map array
     *
     * @param array $map
     *
     * @return static
     */
    public static function newFromArray(array $map): self
    {
        $cmd = '';
        $ext = [];

        // Find tcp message route
        if (isset($map['cmd'])) {
            $cmd = (string)$map['cmd'];
            unset($map['cmd']);
        }

        if (isset($map['data'])) {
            $body = $map['data'];

            // Has ext data for package
            if (isset($map['ext'])) {
                $ext = (array)$map['ext'];
            }
        } else {
            $body = $map;
        }

        return self::new($cmd, $body, $ext);
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
}
