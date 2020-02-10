<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     *      middlewares => [middleware0],
     *  ]
     * ]
     */
    private static $modules = [];

    /**
     * @var array
     * [
     *  controller class => [
     *      prefix => 'home',
     *      middles => [middleware0],
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
     * @param string $class
     * @param string $prefix
     * @param array  $middles
     */
    public static function bindController(string $class, string $prefix, array $middles): void
    {
        self::$commands[$class] = [
            'prefix'  => $prefix ?: Str::getClassName($class, 'Controller'),
            'class'   => $class,
            'routes'  => [], // see bindCommand()
            'middles' => $middles
        ];
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $command
     * @param array  $options [isRoot => bool, opcode => int, middles => array]
     */
    public static function bindCommand(string $class, string $method, string $command, array $options = []): void
    {
        $options['method']  = $method;
        $options['command'] = $command ?: $method;

        self::$commands[$class]['routes'][] = $options;
    }

    /**
     * @param Router $router
     */
    public static function registerTo(Router $router): void
    {
        // Modules
        foreach (self::$modules as $modClass => $modInfo) {
            $modPath = $modInfo['path'];
            $middles = $modInfo['middlewares'];

            $router->addModule($modPath, $modInfo);

            // Commands
            foreach ($modInfo['controllers'] as $ctrlClass) {
                if (!isset(self::$commands[$ctrlClass])) {
                    continue;
                }

                $cInfo  = self::$commands[$ctrlClass];
                $prefix = $cInfo['prefix'];
                // Save module class
                $cInfo['module'] = $modClass;
                $cMiddles = $cInfo['middles'] ? array_merge($middles, $cInfo['middles']): $middles;

                foreach ($cInfo['routes'] as $route) {
                    $cmd   = $route['command'];
                    $cmdId = $route['isRoot'] ? $cmd : $prefix . '.' . $cmd;

                    $router->addCommand($modPath, $cmdId, [$ctrlClass, $route['method']], [
                        'opcode'  => $route['opcode'] ?: $modInfo['defaultOpcode'],
                        'middles' => $route['middles'] ? array_merge($cMiddles, $route['middles']) : $cMiddles,
                    ]);
                }
            }
        }

        // Clear data
        self::$commands = self::$modules = [];
    }
}
