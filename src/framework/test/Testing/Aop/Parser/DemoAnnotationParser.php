<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Testing\Aop\Parser;

use Swoft\Bean\Collector;
use SwoftTest\Testing\Aop\Collector\DemoCollector;

class DemoAnnotationParser
{
    public function parser(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        Collector::$methodAnnotations[$className][$methodName][] = get_class($objectAnnotation);
        DemoCollector::$methodAnnotations[$className][$methodName] = $objectAnnotation;
        return null;
    }
}
