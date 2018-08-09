<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/23
 * Time: 11:55
 */

namespace Swoft\Encrypt\Bean\Collector;

use Swoft\Encrypt\Bean\Annotation\Encrypt;
use Swoft\Bean\CollectorInterface;

/**
 * Class EncryptCollector
 * @package Swoft\Encrypt\Bean\Collector
 */
class EncryptCollector implements CollectorInterface
{
    private static $collector = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    )
    {
        if ($objectAnnotation instanceof Encrypt) {
            self::$collector[$className][$methodName ?: 'classAnnotation'] = $objectAnnotation;
        }
    }

    public static function getCollector()
    {
        return self::$collector;
    }

}