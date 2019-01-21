<?php

namespace Swoft\Bean;

/**
 * Static proxy interface
 *
 * @since 2.0
 */
interface ClassProxyInterface
{
    /**
     * Static proxy
     *
     * @param string $className
     *
     * @return string
     */
    public function proxy(string $className): string;
}