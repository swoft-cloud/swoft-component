<?php

namespace Swoft\Proxy;


use Swoft\Bean\ClassProxyInterface;

/**
 * Class BeanProxy
 *
 * @since 2.0
 */
class BeanProxy implements ClassProxyInterface
{
    /**
     * Proxy class
     *
     * @param string $class
     *
     * @return string
     */
    public function proxy(string $className): string
    {
        return $class;
    }
}