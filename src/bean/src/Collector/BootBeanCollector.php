<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Collector;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Bean\CollectorInterface;

class BootBeanCollector implements CollectorInterface
{
    const TYPE_SERVER = 'server';

    const TYPE_WORKER = 'worker';

    private static $collector = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof BootBean) {
            $key = $objectAnnotation->isServer() ? static::TYPE_SERVER : static::TYPE_WORKER;
            self::$collector[$key][] = $className;
        }
    }

    public static function getCollector(): array
    {
        return self::$collector;
    }
}
