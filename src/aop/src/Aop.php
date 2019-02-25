<?php

namespace Swoft\Aop;

use Swoft\WebSocket\Server\Command\WsServerCommand;

/**
 * Class AopRegister
 *
 * @since 2.0
 */
class Aop
{
    /**
     * Ignore methods
     */
    const IGNORE_METHODS = [
        '__construct',
        'init'
    ];

    /**
     * Class mapping for aspect
     *
     * @var array
     */
    private static $mapping = [];

    /**
     * Register bean aop
     *
     * @param array  $beanNames Bean and alias name
     * @param string $className
     * @param string $method
     * @param array  $methodAnnotations
     */
    public static function register(array $beanNames, string $className, string $method, array $methodAnnotations): void
    {
        $aspects = AspectRegister::getAspects();

        // Sort aspect by order
        $temp = \array_column($aspects, 'order');
        \array_multisort($temp, SORT_ASC, $aspects);

        foreach ($aspects as $aspectClass => $aspect) {
            if (!isset($aspect['point'], $aspect['advice'])) {
                continue;
            }

            // Include
            $pointBeanInclude       = $aspect['point']['bean']['include'] ?? [];
            $pointAnnotationInclude = $aspect['point']['annotation']['include'] ?? [];
            $pointExecutionInclude  = $aspect['point']['execution']['include'] ?? [];

            // Exclude
            $pointBeanExclude       = $aspect['point']['bean']['exclude'] ?? [];
            $pointAnnotationExclude = $aspect['point']['annotation']['exclude'] ?? [];
            $pointExecutionExclude  = $aspect['point']['execution']['exclude'] ?? [];

            // Is include
            $isIncludeBean       = self::isBeanOrAnnotation($beanNames, $pointBeanInclude);
            $isIncludeAnnotation = self::isBeanOrAnnotation($methodAnnotations, $pointAnnotationInclude);
            $isIncludeExecution  = self::isExecution($className, $method, $pointExecutionInclude);

            // Is exclude
            $isExcludeBean       = self::isBeanOrAnnotation($beanNames, $pointBeanExclude);
            $isExcludeAnnotation = self::isBeanOrAnnotation($methodAnnotations, $pointAnnotationExclude);
            $isExcludeExecution  = self::isExecution($className, $method, $pointExecutionExclude);

            $isInclude = $isIncludeBean || $isIncludeAnnotation || $isIncludeExecution;
            $isExclude = $isExcludeBean || $isExcludeAnnotation || $isExcludeExecution;

            if ($isInclude && !$isExclude) {
                self::$mapping[$className][$method][] = $aspect['advice'];
            }
        }
    }

    /**
     * Get match  aspect
     *
     * @param string $className
     * @param string $method
     *
     * @return array
     */
    public static function match(string $className, string $method): array
    {
        // Ignore methods
        if (in_array(strtolower($method), self::IGNORE_METHODS)) {
            return [];
        }

        $aspects = self::$mapping[$className][$method] ?? [];
        return $aspects;
    }

    /**
     * Is bean or annotation
     *
     * @param array $pointAry
     * @param array $classAry
     *
     * @return bool
     */
    private static function isBeanOrAnnotation(array $pointAry, array $classAry): bool
    {
        $intersectAry = \array_intersect($pointAry, $classAry);
        if (empty($intersectAry)) {
            return false;
        }

        return true;
    }

    /**
     * Is execution
     *
     * @param string $class
     * @param string $method
     * @param array  $executions
     *
     * @return bool
     */
    private static function isExecution(string $class, string $method, array $executions): bool
    {
        foreach ($executions as $execution) {
            $executionAry = \explode('::', $execution);
            if (\count($executionAry) < 2) {
                continue;
            }

            // Class
            list($executionClass, $executionMethod) = $executionAry;
            if ($executionClass !== $class) {
                continue;
            }

            // Method
            $reg = '/^(?:' . $executionMethod . ')$/';
            if (\preg_match($reg, $method)) {
                return true;
            }
        }

        return false;
    }
}