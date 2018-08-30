<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Bean\Collector;

use Swoft\Aop\Bean\Annotation\After;
use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\AfterThrowing;
use Swoft\Aop\Bean\Annotation\Around;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\Before;
use Swoft\Aop\Bean\Annotation\PointAnnotation;
use Swoft\Aop\Bean\Annotation\PointBean;
use Swoft\Aop\Bean\Annotation\PointExecution;
use Swoft\Bean\CollectorInterface;

class AspectCollector implements CollectorInterface
{

    private static $aspects = [];

    public static function collect(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        if ($objectAnnotation instanceof AfterReturning) {
            self::collectAfterReturning($className, $methodName);
        } elseif ($objectAnnotation instanceof AfterThrowing) {
            self::collectAfterThrowing($className, $methodName);
        } elseif ($objectAnnotation instanceof Around) {
            self::collectAround($className, $methodName);
        } elseif ($objectAnnotation instanceof Before) {
            self::collectBefore($className, $methodName);
        } elseif ($objectAnnotation instanceof After) {
            self::collectAfter($className, $methodName);
        } elseif ($objectAnnotation instanceof Aspect) {
            self::collectAspect($objectAnnotation, $className);
        } elseif ($objectAnnotation instanceof PointAnnotation) {
            self::collectPointAnnotation($objectAnnotation, $className);
        } elseif ($objectAnnotation instanceof PointBean) {
            self::collectPointBean($objectAnnotation, $className);
        } elseif ($objectAnnotation instanceof PointExecution) {
            self::collectPointExecution($objectAnnotation, $className);
        }
    }

    /**
     * @return void
     */
    private static function collectPointExecution(PointExecution $objectAnnotation, string $className)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        $include = $objectAnnotation->getInclude();
        $exclude = $objectAnnotation->getExclude();

        self::$aspects[$className]['point']['execution'] = [
            'include' => $include,
            'exclude' => $exclude,
        ];
    }

    /**
     * @return void
     */
    private static function collectPointBean(PointBean $objectAnnotation, string $className)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        $include = $objectAnnotation->getInclude();
        $exclude = $objectAnnotation->getExclude();

        self::$aspects[$className]['point']['bean'] = [
            'include' => $include,
            'exclude' => $exclude,
        ];
    }

    /**
     * @return void
     */
    private static function collectPointAnnotation(PointAnnotation $objectAnnotation, string $className)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        $include = $objectAnnotation->getInclude();
        $exclude = $objectAnnotation->getExclude();

        self::$aspects[$className]['point']['annotation'] = [
            'include' => $include,
            'exclude' => $exclude,
        ];
    }

    private static function collectAspect(Aspect $objectAnnotation, string $className)
    {
        $order = $objectAnnotation->getOrder();

        self::$aspects[$className]['order'] = $order;
    }

    /**
     * @return void
     */
    private static function collectAfter(string $className, string $methodName)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        self::$aspects[$className]['advice']['after'] = [$className, $methodName];
    }

    /**
     * @return void
     */
    private static function collectBefore(string $className, string $methodName)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        self::$aspects[$className]['advice']['before'] = [$className, $methodName];
    }

    /**
     * @return void
     */
    private static function collectAround(string $className, string $methodName)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        self::$aspects[$className]['advice']['around'] = [$className, $methodName];
    }

    /**
     * @return void
     */
    private static function collectAfterThrowing(string $className, string $methodName)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }

        self::$aspects[$className]['advice']['afterThrowing'] = [$className, $methodName];
    }

    /**
     * @return void
     */
    private static function collectAfterReturning(string $className, string $methodName)
    {
        if (!isset(self::$aspects[$className])) {
            return null;
        }
        self::$aspects[$className]['advice']['afterReturning'] = [$className, $methodName];
    }

    public static function getCollector(): array
    {
        return self::$aspects;
    }
}
