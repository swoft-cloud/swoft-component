<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Middleware;

use function array_unique;

/**
 * Class MiddlewareRegister
 *
 * @since 2.0
 */
class MiddlewareRegister
{
    /**
     * All Middlewares within class and method
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *          'class' => [
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
     * Register class middleware
     *
     * @param string $name      middleware name
     * @param string $className class name
     *
     * @return void
     */
    public static function registerByClassName(string $name, string $className): void
    {
        $middlewares   = self::$middlewares[$className]['class'] ?? [];
        $middlewares[] = $name;

        self::$middlewares[$className]['class'] = array_unique($middlewares);
    }

    /**
     * Register method middleware
     *
     * @param string $name
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public static function registerByMethodName(string $name, string $className, string $methodName): void
    {
        $middlewares   = self::$middlewares[$className]['methods'][$methodName] ?? [];
        $middlewares[] = $name;

        self::$middlewares[$className]['methods'][$methodName] = array_unique($middlewares);
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return array
     */
    public static function getMiddlewares(string $className, string $methodName): array
    {
        $classMiddlewares  = self::$middlewares[$className]['class'] ?? [];
        $methodMiddlewares = self::$middlewares[$className]['methods'][$methodName] ?? [];

        $middlewares = array_merge($classMiddlewares, $methodMiddlewares);
        $middlewares = array_unique($middlewares);
        return $middlewares;
    }
}