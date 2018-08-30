<?php

namespace Swoft\Bean;

/**
 * Annotaions Data Collector Interface
 */
interface CollectorInterface
{

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    );

    public static function getCollector(): array;
}