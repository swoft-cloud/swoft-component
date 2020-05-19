<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Router;

use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Stdlib\Helper\Str;
use function rtrim;

/**
 * Class RoutesRegister - collect all routes info from annotations
 *
 * @since 2.0
 */
final class RouteRegister
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
     * @param string $className
     *
     * @return bool
     */
    public static function hasRouteByClassName(string $className): bool
    {
        return isset(self::$routes[$className]);
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

                // If use "", eg: `@RequestMapping("")`
                // Will use the route prefix(Controller.prefix) as route.
                if ($routePath === RequestMapping::USE_PREFIX) {
                    $path = $prefix;
                } else {
                    // A route starting with '/' is a separate route
                    // Unused '/' needs to be combined with the controller group into a route
                    $path = $routePath[0] === '/' ? $routePath : $prefix . '/' . $routePath;
                }

                $handler = $class . '@' . $route['action'];

                $router->map($route['method'], $path, $handler, $route['params'], [
                    'name' => $route['name'],
                ]);
            }
        }
    }
}
