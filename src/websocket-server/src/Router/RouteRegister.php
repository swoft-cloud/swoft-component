<?php

namespace Swoft\WebSocket\Server\Router;

use Swoft\Stdlib\Helper\Str;
use function array_merge;

/**
 * Class RouteRegister
 *
 * @since 2.0
 */
final class RouteRegister
{
    /**
     * @var array
     * [
     *  module class => [
     *      path => '/chat/{id}',
     *      params => ['id' => '\d+'],
     *      controllers => ['class1', 'class2'],
     *  ]
     * ]
     */
    private static $modules = [];

    /**
     * @var array
     * [
     *  controller class => [
     *      prefix => 'home',
     *
     *  ]
     * ]
     */
    private static $commands = [];

    /**
     * @param string $class
     * @param array  $option
     */
    public static function bindModule(string $class, array $option): void
    {
        if (isset(self::$modules[$class])) {
            self::$modules[$class] = array_merge(self::$modules[$class], $option);
        } else {
            self::$modules[$class] = $option;
        }
    }

    /**
     * @param string $moduleClass
     * @param string $method
     * @param string $event such as: message, handshake, open, close
     */
    public static function bindEvent(string $moduleClass, string $method, string $event): void
    {
        self::$modules[$moduleClass]['eventMethods'][$event] = $method;
    }

    /**
     * @param string $controllerClass
     * @param string $prefix
     */
    public static function bindController(string $controllerClass, string $prefix): void
    {
        self::$commands[$controllerClass] = [
            'prefix' => $prefix ?: Str::getClassName($controllerClass, 'Controller'),
            'class'  => $controllerClass,
            'routes' => [], // see bindCommand()
        ];
    }

    /**
     * @param string $controllerClass
     * @param string $method
     * @param string $command
     */
    public static function bindCommand(string $controllerClass, string $method, string $command): void
    {
        self::$commands[$controllerClass]['routes'][] = [
            'method'  => $method,
            'command' => $command ?: $method,
        ];
    }

    /**
     * @param Router $router
     */
    public static function registerTo(Router $router): void
    {
        // Modules
        foreach (self::$modules as $mdlClass => $mdlInfo) {
            $path = $mdlInfo['path'];
            $router->addModule($path, $mdlInfo);

            // Commands
            foreach ($mdlInfo['controllers'] as $ctrlClass) {
                if (!isset(self::$commands[$ctrlClass])) {
                    continue;
                }

                $info   = self::$commands[$ctrlClass];
                $prefix = $info['prefix'];
                // save module class
                $info['module'] = $mdlClass;

                foreach ($info['routes'] as $route) {
                    $cmdId = $prefix . '.' . $route['command'];
                    $router->addCommand($path, $cmdId, [$ctrlClass, $route['method']]);
                }
            }
        }

        // Clear data
        self::$commands = self::$modules = [];
    }
}
