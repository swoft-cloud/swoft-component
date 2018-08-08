<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/23
 * Time: 12:00
 */

namespace Swoft\Encrypt\Bean\Parser;

use Swoft\Encrypt\Bean\Collector\EncryptCollector;
use Swoft\Bean\Collector;
use Swoft\Bean\Parser\AbstractParser;

/**
 * Class EncryptParser
 * @package Swoft\Encrypt\Bean\Parser
 */
class EncryptParser extends AbstractParser
{
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    )
    {
        Collector::$methodAnnotations[$className][$methodName][] = get_class($objectAnnotation);
        EncryptCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
    }
}