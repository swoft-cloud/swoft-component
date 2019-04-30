<?php declare(strict_types=1);

namespace Swoft\Http\Server\Router;

use Swoft\Stdlib\Helper\Str;

/**
 * Class RoutesRegister - collect all routes info from annotations
 *
 * @since 2.0
 */
class RouteRegister
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

            // Group/Controller prefix
            if (!$prefix = $mapping['prefix']) {
                $prefix = '/' . Str::getClassName($class, $suffix);
            }

            $prefix = rtrim($prefix, '/');

            // Register a set of routes corresponding to the controller
            foreach ($mapping['routes'] as $route) {
                if (!isset($route['route'], $route['method'], $route['action'])) {
                    continue;
                }

                // Ensure is not empty
                $routePath = $route['route'] ?: $route['action'];

                // 以 '/' 开头的路由是一个单独的路由
                // 未使用 '/' 需要和控制器组拼成一个路由
                $path    = $routePath[0] === '/' ? $routePath : $prefix . '/' . $routePath;
                $handler = $class . '@' . $route['action'];

                $router->map($route['method'], $path, $handler, $route['params']);
            }
        }

        // clear data
        self::$routes = [];
    }
}
