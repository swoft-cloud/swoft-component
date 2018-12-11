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
