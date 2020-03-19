<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Router;

use Swoft\Stdlib\Helper\Str;
use Swoft\Tcp\Server\Exception\TcpServerRouteException;
use function array_merge;

/**
 * Class RouteRegister
 *
 * @since 2.0.3
 */
final class RouteRegister
{
    /**
     * Raw tcp controller, methods data
     *
     * [
     *  controller class => [
     *      prefix => 'home',
     *      routes => [
     *          root  => false,
     *          route  => string,
     *          method => 'index',
     *          middles => [middleware1],
     *      ],
     *      middles => [middleware0],
     *  ]
     * ]
     *
     * @var array
     */
    private static $controllers = [];

    /**
     * @param string $class
     * @param string $prefix
     * @param array  $middlewares
     */
    public static function bindController(string $class, string $prefix, array $middlewares): void
    {
        self::$controllers[$class] = [
            'prefix'  => $prefix ?: Str::getClassName($class, 'Controller'),
            'class'   => $class,
            'routes'  => [], // see bindCommand()
            'middles' => $middlewares,
        ];
    }

    /**
     * @param string $class
     * @param string $method
     * @param array  $info [route => string, root => bool, middles => array]
     */
    public static function bindCommand(string $class, string $method, array $info): void
    {
        $info['method'] = $method;

        self::$controllers[$class]['routes'][] = $info;
    }

    /**
     * @param Router $router
     *
     * @throws TcpServerRouteException
     */
    public static function registerTo(Router $router): void
    {
        $delimiter = $router->getDelimiter();

        foreach (self::$controllers as $ctrlClass => $group) {
            $prefix  = $group['prefix'];
            $middles = $group['middles'];

            // Register routes
            foreach ($group['routes'] as $route) {
                $path = $route['route'];

                // Is not root command route. prepend group prefix.
                if ($prefix && !$route['root']) {
                    $path = $prefix . $delimiter . $path;
                }

                $router->add($path, [$ctrlClass, $route['method']], [
                    'middles' => $route['middles'] ? array_merge($middles, $route['middles']) : $middles,
                ]);
            }
        }

        // Clear data
        self::$controllers = [];
    }
}
