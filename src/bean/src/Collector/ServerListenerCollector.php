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

use Swoft\Bean\Annotation\BeforeStart;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bean\CollectorInterface;
use Swoft\Bootstrap\SwooleEvent;

class ServerListenerCollector implements CollectorInterface
{
    private static $listeners = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof BeforeStart) {
            self::$listeners[SwooleEvent::ON_BEFORE_START][] = $className;
        } elseif ($objectAnnotation instanceof ServerListener) {
            $events = $objectAnnotation->getEvent();

            foreach ($events as $event) {
                self::$listeners[$event][] = $className;
            }
        }
    }

    public static function getCollector(): array
    {
        return self::$listeners;
    }
}
