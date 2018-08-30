<?php

namespace Swoft\Bean\Collector;

use Swoft\Bean\Annotation\Pool;
use Swoft\Bean\CollectorInterface;

class PoolCollector implements CollectorInterface
{
    private static $pools = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Pool) {
            $poolName = $objectAnnotation->getName();
            $poolName = empty($poolName) ? $className : $poolName;
            self::$pools[$poolName] = $className;
        }
    }

    public static function getCollector(): array
    {
        return self::$pools;
    }

}