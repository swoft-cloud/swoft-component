<?php

namespace Swoft\WebSocket\Server\Router;

use Swoft\Helper\CLog;
use Swoft\Stdlib\Helper\Str;

/**
 * Class RouteRegister
 * @since 2.0
 */
final class RouteRegister
{
    /**
     * @var array
     */
    private static $modules = [];

    /**
     * @var array
     */
    private static $commands = [];

    /**
     * @param string $class
     * @param array  $option
     */
    public static function bindModule(string $class, array $option): void
    {
        if (isset(self::$modules[$class])) {
            self::$modules[$class] = \array_merge(self::$modules[$class], $option);
        } else {
            self::$modules[$class] = $option;
        }
    }

    /**
     * @param string $moduleClass
     * @param string $method
     * @param string $event such as: message, handShake, open, close
     */
    public static function bindEvent(string $moduleClass, string $method, string $event): void
    {
        self::$modules[$moduleClass]['eventMethods'][$event] = $method;
    }

    /**
     * @param string $moduleClass
     * @param string $controllerClass
     * @param string $prefix
     */
    public static function bindController(string $moduleClass, string $controllerClass, string $prefix): void
    {
        self::$modules[$moduleClass]['controllers'][] = $controllerClass;

        self::$commands[$controllerClass] = [
            'prefix' => $prefix ?: Str::getClassName($controllerClass, 'Controller'),
            'module' => $moduleClass,
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
            $router->addModule($mdlInfo['path'], $mdlInfo);
        }

        // Commands
        foreach (self::$commands as $ctrlClass => $info) {
            $path = self::$modules[$info['module']]['path'];
            foreach ($info['routes'] as $route) {
                $id = $info['prefix'] . '.' . $route['command'];
                $router->addCommand($path, $id, [$ctrlClass, $route['method']]);
            }
        }

        CLog::info(
            'webSocket server add %d module, %d message command',
            $router->getModuleCount(),
            $router->getCounter()
        );

        // Clear data
        self::$commands = self::$modules = [];
    }
}
