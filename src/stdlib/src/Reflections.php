<?php declare(strict_types=1);

namespace Swoft\Stdlib;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use function count;

/**
 * Class Reflections
 *
 * @since 2.0
 */
final class Reflections
{
    /**
     * Reflection information pool
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'comments' => 'class doc comments',
     *         'methods'  => [
     *             'methodName' => [
     *                'params'     => [
     *                    'argName',  // like `name`
     *                    'argType',  // like `int`
     *                    null // like `$arg`
     *                ],
     *                'comments'   => 'method doc comments',
     *                'returnType' => 'returnType/null'
     *            ]
     *         ]
     *     ]
     * ]
     */
    private static $pool = [];

    /**
     * @return int
     */
    public static function count(): int
    {
        return count(self::$pool);
    }

    /**
     * @param string $className
     *
     * @return array
     * @throws ReflectionException
     */
    public static function get(string $className): array
    {
        // Not exist, cache it
        if (!isset(self::$pool[$className])) {
            self::cache($className);
        }

        return self::$pool[$className];
    }

    /**
     * @param string $className
     *
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public static function cache(string $className): ReflectionClass
    {
        $reflectionClass = new ReflectionClass($className);

        // Cache some metadata for the class
        self::cacheReflectionClass($reflectionClass);

        return $reflectionClass;
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @throws ReflectionException
     */
    public static function cacheReflectionClass(ReflectionClass $reflectionClass): void
    {
        $className = $reflectionClass->getName();
        if (isset(self::$pool[$className])) {
            return;
        }

        $reflectionMethods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        self::$pool[$className]['name']     = $reflectionClass->getName();
        self::$pool[$className]['comments'] = $reflectionClass->getDocComment();

        foreach ($reflectionMethods as $reflectionMethod) {
            $methodName   = $reflectionMethod->getName();
            $methodParams = [];

            foreach ($reflectionMethod->getParameters() as $parameter) {
                $defaultValue = null;
                if ($parameter->isDefaultValueAvailable()) {
                    $defaultValue = $parameter->getDefaultValue();
                }

                $methodParams[] = [
                    $parameter->getName(),
                    $parameter->getType(),
                    $defaultValue
                ];
            }

            self::$pool[$className]['methods'][$methodName]['params']     = $methodParams;
            self::$pool[$className]['methods'][$methodName]['comments']   = $reflectionMethod->getDocComment();
            self::$pool[$className]['methods'][$methodName]['returnType'] = $reflectionMethod->getReturnType();
        }
    }
}
