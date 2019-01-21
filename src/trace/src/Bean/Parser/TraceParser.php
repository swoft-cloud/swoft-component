<?php

namespace Swoft\Trace\Bean\Parser;

use Swoft\Bean\Collector;
use Swoft\Bean\Parser\AbstractParser;
use Swoft\Trace\Bean\Annotation\Trace;
use Swoft\Trace\Tracer;

/**
 * Class TraceParser
 *
 * @package Swoft\Trace\Bean\Parser
 */
class TraceParser extends AbstractParser
{
    /**
     * @param string $className
     * @param Trace  $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     * @return null
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        Collector::$methodAnnotations[$className][$methodName][] = \get_class($objectAnnotation);
        return null;
    }
}
