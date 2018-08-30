<?php

namespace Swoft\Console\Bean\Parser;

use Swoft\Bean\Parser\AbstractParser;
use Swoft\Console\Bean\Collector\CommandCollector;

class MappingParser extends AbstractParser
{

    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        CommandCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
    }
}
