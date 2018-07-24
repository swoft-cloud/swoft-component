<?php

namespace SwoftTest\Aop\Parser;

use Swoft\Bean\Collector;
use SwoftTest\Aop\Collector\DemoCollector;

class DemoAnnotationParser
{
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        Collector::$methodAnnotations[$className][$methodName][] = get_class($objectAnnotation);
        DemoCollector::$methodAnnotations[$className][$methodName] = $objectAnnotation;
        return null;
    }
}