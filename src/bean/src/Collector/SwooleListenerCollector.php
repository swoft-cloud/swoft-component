<?php

namespace Swoft\Bean\Collector;

use Swoft\Bean\Annotation\SwooleListener;
use Swoft\Bean\CollectorInterface;
use Swoft\Bootstrap\SwooleEvent;

class SwooleListenerCollector implements CollectorInterface
{
    private static $listeners = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if (! ($objectAnnotation instanceof SwooleListener)) {
            return;
        }

        $events = $objectAnnotation->getEvent();
        if (empty($events) || ! \is_array($events)) {
            return;
        }

        $type = $objectAnnotation->getType();
        foreach ($events as $event) {
            if (! SwooleEvent::isSwooleEvent($event)) {
                continue;
            }
            if ($type === SwooleEvent::TYPE_PORT) {
                $order = $objectAnnotation->getOrder();
                self::$listeners[$type][$order][$event] = $className;
            } elseif ($type === SwooleEvent::TYPE_SERVER) {
                self::$listeners[$type][$event] = $className;
            }
        }
    }

    public static function getCollector(): array
    {
        return self::$listeners;
    }

}
