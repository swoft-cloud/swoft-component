<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Router;

use Swoft\Stdlib\Helper\Str;

/**
 * Class RouteRegister
 *
 * @since 2.0.3
 */
final class RouteRegister
{
    /**
     * @var array
     * [
     *  controller class => [
     *      prefix => 'home',
     *      path => '/chat/{id}',
     *      params => ['id' => '\d+'],
     *  ]
     * ]
     */
    private static $routes = [];

    /**
     * @param string $controllerClass
     * @param string $prefix
     */
    public static function bindController(string $controllerClass, string $prefix): void
    {
        self::$routes[$controllerClass] = [
            'prefix' => $prefix ?: Str::getClassName($controllerClass, 'Controller'),
            'class'  => $controllerClass,
            'routes' => [], // see bindCommand()
        ];
    }

    /**
     * @param string $controllerClass
     * @param string $method
     * @param array  $info
     */
    public static function bindCommand(string $controllerClass, string $method, array $info): void
    {
        $info['method'] = $method;

        self::$routes[$controllerClass]['routes'][] = $info;
    }

    /**
     * @param Router $router
     */
    public static function registerTo(Router $router): void
    {
        // Modules
        foreach (self::$routes as $ctrlClass => $group) {
            $prefix = $group['prefix'];
            // save module class
            $group['controller'] = $ctrlClass;

            foreach ($group['routes'] as $route) {
                $cmdId = $prefix . '.' . $route['command'];
                $router->add($path, [$ctrlClass, $route['method']]);
            }
        }

        // Clear data
        self::$routes = [];
    }
}
