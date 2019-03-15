<?php declare(strict_types=1);


namespace Swoft\Http\Server\Middleware;

/**
 * Class MiddlewareRegister
 *
 * @since 2.0
 */
class MiddlewareRegister
{
    /**
     * All Middlewares within controller and action
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *          'controller' => [
     *              'middlewareName',
     *              'middlewareName',
     *              'middlewareName'
     *          ]
     *          'methods' => [
     *              'actionName' => [
     *                  'middlewareName',
     *                  'middlewareName',
     *                  'middlewareName'
     *              ]
     *          ]
     *     ]
     * ]
     */
    private static $middlewares = [];

    /**
     * Register controller middleware
     *
     * @param string $name      middleware name
     * @param string $className class name
     *
     * @return void
     */
    public static function registerByClassName(string $name, string $className): void
    {
        $groupMiddlewares   = self::$middlewares[$className]['controller'] ?? [];
        $groupMiddlewares[] = $name;

        self::$middlewares[$className]['controller'] = \array_unique($groupMiddlewares);
    }

    /**
     * Register action middleware
     *
     * @param string $name
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public static function registerByMethodName(string $name, string $className, string $methodName): void
    {
        $groupMiddlewares   = self::$middlewares[$className]['action'][$methodName] ?? [];
        $groupMiddlewares[] = $name;

        self::$middlewares[$className]['methods'][$methodName] = \array_unique($groupMiddlewares);
    }

    /**
     * @return array
     */
    public static function getMiddlewares(): array
    {
        return self::$middlewares;
    }
}