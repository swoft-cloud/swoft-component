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

namespace Swoft\Console\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Bean\Annotation\Mapping;

class CommandCollector implements CollectorInterface
{
    private static $commandMapping = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Command) {
            self::collectCommand($className, $objectAnnotation);
        } elseif ($objectAnnotation instanceof Mapping) {
            self::collectMapping($className, $objectAnnotation, $methodName);
        } elseif ($objectAnnotation === null && isset(self::$commandMapping[$className])) {
            self::collectWithoutAnnotation($className, $methodName);
        }
    }

    public static function getCollector(): array
    {
        return self::$commandMapping;
    }

    private static function collectCommand(string $className, Command $objectAnnotation)
    {
        $commandName = $objectAnnotation->getName();
        $coroutine = $objectAnnotation->isCoroutine();
        $server = $objectAnnotation->isServer();

        self::$commandMapping[$className]['name'] = $commandName;
        self::$commandMapping[$className]['enabled'] = $objectAnnotation->isEnabled();
        self::$commandMapping[$className]['coroutine'] = $coroutine;
        self::$commandMapping[$className]['server'] = $server;
    }

    private static function collectMapping(string $className, Mapping $objectAnnotation, string $methodName)
    {
        $mapped = $objectAnnotation->getName();

        self::$commandMapping[$className]['routes'][] = [
            'mappedName' => $mapped,
            'methodName' => $methodName,
        ];
    }

    private static function collectWithoutAnnotation(string $className, string $methodName)
    {
        self::$commandMapping[$className]['routes'][] = [
            'mappedName' => '',
            'methodName' => $methodName,
        ];
    }
}
