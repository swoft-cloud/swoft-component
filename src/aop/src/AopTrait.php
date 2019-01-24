<?php

namespace Swoft\Aop;

/**
 * Trait AopTrait
 *
 * @since 2.0
 */
trait AopTrait
{
    /**
     * Proxy call
     *
     * @param string $className
     * @param string $methodName
     * @param array  $args
     *
     * @return mixed
     */
    public function __proxyCall(string $className, string $methodName, array $args)
    {
        $mathAspects = Aop::match($className, $methodName);
        if (empty($mathAspects)) {
            return $this->__invokeTarget($methodName, $args);
        }

        $handler = new Handler($this, $methodName, $args, $mathAspects);
        return $handler->invokeAspect();
    }

    /**
     * Invoke target method
     *
     * @param string $methodName
     * @param array  $args
     *
     * @return mixed
     */
    public function __invokeTarget(string $methodName, array $args)
    {
        return parent::{$methodName}(...$args);
    }
}