<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 15:06
 */

namespace Swoft\Http\Server\Router;

use Swoft\Http\Server\Helper\RouteHelper;

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

    /**
     * @param string $class
     * @param string $prefix
     */
    public static function addPrefix(string $class, string $prefix): void
    {
        self::$routes[$class]['prefix'] = $prefix;
    }

    /**
     * @param string $class
     * @param array  $routeInfo
     */
    public static function addRoute(string $class, array $routeInfo): void
    {
        self::$routes[$class]['routes'][] = $routeInfo;
    }

    /**
     * @param Router $router
     */
    public static function registerRoutes(Router $router): void
    {
        $suffix = $router->controllerSuffix;

        foreach (self::$routes as $class => $mapping) {
            if (!isset($mapping['prefix'], $mapping['routes'])) {
                continue;
            }

            // controller prefix
            $prefix = RouteHelper::getControllerPrefix($mapping['prefix'], $class, $suffix);

            // Register a set of routes corresponding to the controller
            foreach ($mapping['routes'] as $route) {
                if (!isset($route['route'], $route['method'], $route['action'])) {
                    continue;
                }

                // ensure is not empty
                $mapRoute = $route['route'] ?: $route['action'];

                // 以 '/' 开头的路由是一个单独的路由
                // 未使用 '/' 需要和控制器组拼成一个路由
                $path    = $mapRoute[0] === '/' ? $mapRoute : $prefix . '/' . $mapRoute;
                $handler = $class . '@' . $route['action'];

                $router->map($route['method'], $path, $handler, $route['params']);
            }
        }

        // clear data
        self::$routes = [];
    }
}
