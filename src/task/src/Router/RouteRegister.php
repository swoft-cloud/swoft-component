<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Router;

class RouteRegister
{
    /**
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *          'name' => 'taskName',
     *          'mapping' => [
     *             'methodName' => [
     *                  'name' => 'mappingName',
     *                  'method' => 'methodName',
     *              ]
     *          ]
     *      ]
     * ]
     */
    private static $tasks = [];

    /**
     * @param string $className
     * @param string $taskName
     */
    public static function registerByClassName(string $className, string $taskName): void
    {
        self::$tasks[$className]['name'] = $taskName;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $mappingName
     */
    public static function registerByMethodName(string $className, string $methodName, string $mappingName): void
    {
        // Fix empty name
        if (empty($mappingName)) {
            $mappingName = $methodName;
        }

        self::$tasks[$className]['mapping'][$methodName] = [
            'name'   => $mappingName,
            'method' => $methodName
        ];
    }

    /**
     * @param Router $router
     */
    public static function registerRoutes(Router $router): void
    {
        foreach (self::$tasks as $className => $task) {
            $mapping = $task['mapping'] ?? [];

            if (!$mapping) {
                continue;
            }

            $name = $task['name'] ?: $className;

            foreach ($mapping as $methodName => $map) {
                $mappingName = $map['name'] ?? '';
                if (empty($mappingName)) {
                    continue;
                }

                $router->addRoute($className, $name, $mappingName, $methodName);
            }
        }
    }
}
