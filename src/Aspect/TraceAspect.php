<?php

namespace Swoft\Trace\Aspect;

use Swoft\Aop\JoinPoint;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\Before;
use Swoft\Bean\Annotation\PointAnnotation;
use Swoft\Trace\Bean\Annotation\Trace;
use Swoft\Trace\Tracer;

/**
 * Trace aspect
 * @Aspect()
 * @PointAnnotation(
 *     include={
 *         Trace::class
 *     }
 * )
 */
class TraceAspect
{

    /**
     * @Before()
     * @param JoinPoint $joinPoint
     */
    public function before(JoinPoint $joinPoint)
    {
        /** @var Tracer $tracer */
        $tracer = bean(Tracer::class);
        $tracer->trace(\get_class($joinPoint->getTarget()) . '::' . $joinPoint->getMethod());
    }

}
