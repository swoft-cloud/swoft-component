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
