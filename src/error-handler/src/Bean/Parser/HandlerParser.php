<?php

namespace Swoft\ErrorHandler\Bean\Parser;

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Parser\AbstractParser;
use Swoft\ErrorHandler\Bean\Annotation\Handler;
use Swoft\ErrorHandler\Bean\Collector\ExceptionHandlerCollector;

/**
 * Class HandlerParser
 *
 * @package Swoft\ErrorHandler\Bean\Parser
 */
class HandlerParser extends AbstractParser
{
    /**
     * @param string $className
     * @param Handler $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null $propertyValue
     * @return array
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        ExceptionHandlerCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);

        return [$className, Scope::SINGLETON, ''];
    }
}