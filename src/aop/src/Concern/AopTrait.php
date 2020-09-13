<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop\Concern;

use ReflectionException;
use Swoft\Aop\Aop;
use Swoft\Aop\AspectHandler;
use Swoft\Stdlib\Reflections;
use Throwable;

/**
 * Class AopTrait
 *
 * @since 2.0
 */
trait AopTrait
{
    /**
     * Proxy call
     *
     * @param string $className
     * @param string $methodName
     * @param array  $args
     *
     * @return mixed
     * @throws Throwable
     * @noinspection MagicMethodsValidityInspection
     */
    public function __proxyCall(string $className, string $methodName, array $args)
    {
        $mathAspects = Aop::match($className, $methodName);
        if (!$mathAspects) {
            return $this->__invokeTarget($methodName, $args);
        }

        /* @var AspectHandler $aspectHandler */
        $aspectHandler = bean(AspectHandler::class);
        $aspectHandler->setTarget($this);
        $aspectHandler->setMethodName($methodName);
        $aspectHandler->setArgs($args);
        $aspectHandler->setAspects($mathAspects);
        $aspectHandler->setClassName($className);

        $argsMap = $this->getArgsMap($className, $methodName, $args);
        $aspectHandler->setArgsMap($argsMap);

        return $aspectHandler->invokeAspect();
    }

    /**
     * Invoke target method
     *
     * @param string $methodName
     * @param array  $args
     *
     * @return mixed
     * @noinspection MagicMethodsValidityInspection
     */
    public function __invokeTarget(string $methodName, array $args)
    {
        return parent::{$methodName}(...$args);
    }

    /**
     * @param string $className
     * @param string $method
     * @param array  $args
     *
     * @return array
     * @throws ReflectionException
     */
    public function getArgsMap(string $className, string $method, array $args): array
    {
        $reflections = Reflections::get($className);

        $argsMap = [];
        $params  = $reflections['methods'][$method]['params'];

        // Empty params
        if (empty($params)) {
            return [];
        }

        // Build arg map
        foreach ($params as $index => $param) {
            [$name, , $default] = $param;

            if (isset($args[$index])) {
                $argsMap[$name] = $args[$index];
                continue;
            }

            $argsMap[$name] = $default;
        }

        return $argsMap;
    }
}
