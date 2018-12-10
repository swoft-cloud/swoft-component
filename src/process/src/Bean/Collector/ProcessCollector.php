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

namespace Swoft\Process\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\Process\Bean\Annotation\Process;

/**
 * The process collector
 */
class ProcessCollector implements CollectorInterface
{
    /**
     * @var array
     */
    private static $processes = [];

    /**
     * @param string $className
     * @param object $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     *
     * @return void
     */
    public static function collect(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        if ($objectAnnotation instanceof Process) {
            $num       = $objectAnnotation->getNum();
            $name      = $objectAnnotation->getName();
            $boot      = $objectAnnotation->isBoot();
            $inout     = $objectAnnotation->isInout();
            $pipe      = $objectAnnotation->isPipe();
            $coroutine = $objectAnnotation->isCoroutine();
            $name      = empty($name) ? $className : $name;

            self::$processes[$name] = [
                'name'  => $name,
                'num'   => $num,
                'boot'  => $boot,
                'pipe'  => $pipe,
                'inout' => $inout,
                'co'    => $coroutine,
                'class' => $className,
            ];

            return;
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$processes;
    }
}
