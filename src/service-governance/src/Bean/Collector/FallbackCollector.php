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
use Swoft\Sg\Bean\Annotation\Fallback;

/**
 * Fallback collector
 */
class FallbackCollector implements CollectorInterface
{
    /**
     * @var array Fallback handler list
     */
    private static $fallbacks = [];

    /**
     * @param string $className
     * @param null   $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     * @return void
     */
    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Fallback) {
            $name = $objectAnnotation->getName();
            $fallbackName = empty($name)?$className:$name;
            self::$fallbacks[$fallbackName] = $className;
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$fallbacks;
    }
}
