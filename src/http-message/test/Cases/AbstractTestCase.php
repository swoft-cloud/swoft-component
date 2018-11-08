<?php

namespace SwoftTest\HttpMessage;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Bean\Collector\MiddlewareCollector;

/**
 * AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    public function getMiddleware($controllerClass, $action)
    {
        $actionMiddlewares = [];
        $collector = MiddlewareCollector::getCollector();
        $collectedMiddlewares = $collector[$controllerClass]['middlewares']??[];

        // Get group middleware from Collector
        if ($controllerClass) {
            $collect = $collectedMiddlewares['group'] ?? [];
            $collect && $actionMiddlewares = array_merge($actionMiddlewares, $collect);
        }
        // Get the specified action middleware from Collector
        if ($action) {
            $collect = $collectedMiddlewares['actions'][$action]??[];
            $collect && $actionMiddlewares = array_merge($actionMiddlewares, $collect ?? []);
        }
        return $actionMiddlewares;
    }
}