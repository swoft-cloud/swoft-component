<?php declare(strict_types=1);

namespace Swoft\Bean\Concern;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Container;

/**
 * Class Prototype
 *
 * @since 2.0
 */
trait PrototypeTrait
{
    /**
     * Get instance from container
     *
     * @return static
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected static function __instance()
    {
        return \bean(static::class);
    }
}
