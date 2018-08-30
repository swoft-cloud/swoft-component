<?php

namespace Swoft\Console\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Bean\Annotation\Mapping;
use Swoft\Http\Server\Command\ServerCommand;

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
            var_dump(get_class($objectAnnotation));
            self::collectCommand($className, $objectAnnotation);
        } elseif ($objectAnnotation instanceof Mapping) {
            self::collectMapping($className, $objectAnnotation, $methodName);
        } elseif ($objectAnnotation === null && isset(self::$commandMapping[$className])) {
            self::collectWithoutAnnotation($className, $methodName);
        }
    }

    private static function collectCommand(string $className, Command $objectAnnotation)
    {
        var_dump(0 . $className);
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
        var_dump(1 . $className . $methodName);

        self::$commandMapping[$className]['routes'][] = [
            'mappedName' => $mapped,
            'methodName' => $methodName,
        ];
    }

    private static function collectWithoutAnnotation(string $className, string $methodName)
    {
        var_dump(2 . $className . $methodName);
        self::$commandMapping[$className]['routes'][] = [
            'mappedName' => '',
            'methodName' => $methodName,
        ];
    }

    public static function getCollector(): array
    {
        return self::$commandMapping;
    }
}
