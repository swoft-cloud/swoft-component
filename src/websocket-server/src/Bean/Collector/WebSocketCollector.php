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

namespace Swoft\WebSocket\Server\Bean\Collector;

use Swoft\Bean\CollectorInterface;
use Swoft\WebSocket\Server\Bean\Annotation\WebSocket;

/**
 * Class WsCollector
 * @package Swoft\View\Bean\Collector
 */
class WebSocketCollector implements CollectorInterface
{
    /**
     * @var array
     */
    private static $controllers = [];

    /**
     * @param string $className
     * @param WebSocket $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     */
    public static function collect(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        if ($objectAnnotation instanceof WebSocket) {
            $path = $objectAnnotation->getPath();

            self::$controllers[$path] = [
                'path' => $objectAnnotation->getPath(),
                'handler' => $className,
            ];
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$controllers;
    }
}
