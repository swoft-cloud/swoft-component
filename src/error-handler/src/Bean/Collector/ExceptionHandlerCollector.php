<?php

namespace Swoft\ErrorHandler\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\ErrorHandler\Bean\Annotation\Handler;

/**
 * Class ExceptionHandlerCollector
 *
 * @package Swoft\ErrorHandler\Bean\Collector
 */
class ExceptionHandlerCollector implements CollectorInterface
{
    /**
     * @var array
     */
    private static $handlers = [];

    /**
     * @param string $className
     * @param object $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null $propertyValue
     * @return mixed|void
     */
    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Handler) {
            $exceptionClass = $objectAnnotation->getException();
            self::$handlers[$exceptionClass] = [
                $className,
                $methodName
            ];
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$handlers;
    }

}