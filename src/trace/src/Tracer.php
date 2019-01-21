<?php

namespace Swoft\Trace;

use Swoft\Bean\Annotation\Bean;
use Swoft\Core\RequestContext;

/**
 * @Bean()
 */
class Tracer
{

    /**
     * @param string $method
     */
    public function trace(string $method)
    {
        RequestContext::setContextDataByKey('callStack', array_merge($this->getCallStack(), [$method]));
    }

    /**
     * @return array
     */
    public function getCallStack(): array
    {
        return (array)RequestContext::getContextDataByKey('callStack');
    }

}