<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 15:06
 */

namespace Swoft\Http\Server\Router;

/**
 * Class RoutesCollector
 * @package Swoft\Http\Server\Router
 */
class RoutesCollector
{
    /**
     * @var array
     */
    private static $routes = [];

    public static function addPrefix(string $class, string $prefix): void
    {
        self::$routes[$class]['prefix'] = $prefix;
    }

    public static function addRoute(string $class, array $routeInfo): void
    {
        self::$routes[$class]['routes'][] = $routeInfo;

        // if ($objectAnnotation === null && isset(self::$routes[$className])) {
        //     self::$routes[$class]['routes'][] = [
        //         'route'  => '',
        //         'methods' => [RequestMethod::GET, RequestMethod::POST],
        //         'action' => $method,
        //     ];
        // }
    }

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * clear data
     */
    public static function clear(): void
    {
        self::$routes = [];
    }
}
