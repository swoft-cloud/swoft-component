<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop;

use Swoft\Bean\Annotation\Bean;
use Swoft\Aop\Bean\Collector\AspectCollector;

/**
 * @Bean()
 */
class Aop implements AopInterface
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @var array
     */
    private $aspects = [];

    /**
     * @return void
     */
    public function init()
    {
        // Register aspects by aspect annotation collector
        $this->register(AspectCollector::getCollector());
    }

    /**
     * Match aop
     *
     * @param string $beanName    Bean name
     * @param string $class       Class name
     * @param string $method      Method name
     * @param array  $annotations The annotations of method
     */
    public function match(string $beanName, string $class, string $method, array $annotations)
    {
        foreach ($this->aspects as $aspectClass => $aspect) {
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

            $includeMath = $this->matchBeanAndAnnotation([$beanName], $pointBeanInclude) || $this->matchBeanAndAnnotation($annotations, $pointAnnotationInclude)
                || $this->matchExecution($class, $method, $pointExecutionInclude);

            $excludeMath = $this->matchBeanAndAnnotation([$beanName], $pointBeanExclude) || $this->matchBeanAndAnnotation($annotations, $pointAnnotationExclude)
                || $this->matchExecution($class, $method, $pointExecutionExclude);

            if ($includeMath && !$excludeMath) {
                $this->map[$class][$method][] = $aspect['advice'];
            }
        }
    }

    /**
     * Register aspects
     *
     * @param array $aspects
     */
    public function register(array $aspects)
    {
        $temp = \array_column($aspects, 'order');
        \array_multisort($temp, SORT_ASC, $aspects);
        $this->aspects = $aspects;
    }

    /**
     * @return array
     */
    public function getAspects(): array
    {
        return $this->aspects;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * Match bean and annotation
     *
     * @param array $pointAry
     * @param array $classAry
     *
     * @return bool
     */
    private function matchBeanAndAnnotation(array $pointAry, array $classAry): bool
    {
        $intersectAry = \array_intersect($pointAry, $classAry);
        if (empty($intersectAry)) {
            return false;
        }

        return true;
    }

    /**
     * Match execution
     *
     * @param string $class
     * @param string $method
     * @param array  $executions
     *
     * @return bool
     */
    private function matchExecution(string $class, string $method, array $executions): bool
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
