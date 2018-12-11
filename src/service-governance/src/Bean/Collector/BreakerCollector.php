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

namespace Swoft\Sg\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\Sg\Bean\Annotation\Breaker;

/**
 * the collector of breaker
 */
class BreakerCollector implements CollectorInterface
{
    /**
     * @var array
     */
    private static $breakers = [];

    /**
     * @param string $className
     * @param null   $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     *
     * @return void
     */
    public static function collect(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        if ($objectAnnotation instanceof Breaker) {
            $breakerName = $objectAnnotation->getName();
            $breakerName = empty($breakerName) ? $className : $breakerName;

            self::$breakers[$breakerName] = $className;
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$breakers;
    }
}
