<?php

namespace Swoft\Bean;

/**
 * Dynamic proxy interface
 *
 * @since 2.0
 */
interface ObjectProxyInterface
{
    /**
     * Dynamic proxy
     *
     * @param object $object
     *
     * @return object
     */
    public function proxy($object);
}