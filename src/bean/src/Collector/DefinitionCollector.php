<?php

namespace Swoft\Bean\Collector;

use Swoft\Bean\Annotation\Definition;
use Swoft\Bean\CollectorInterface;

class DefinitionCollector implements CollectorInterface
{

    private static $definitions = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Definition) {
            $name = $objectAnnotation->getName();
            self::$definitions[$className] = empty($name) ? $className : $name;
        }
    }

    public static function getCollector(): array
    {
        return self::$definitions;
    }
}