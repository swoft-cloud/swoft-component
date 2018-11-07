<?php

namespace Swoft\ErrorHandler;

use Swoft\App;
use Swoft\Core\RequestContext;
use Swoft\Helper\PhpHelper;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;

/**
 * Class ErrorHandler
 *
 * @package Swoft\ErrorHandler
 */
class ErrorHandler
{

    /**
     * @param \Throwable $throwable
     * @return array|mixed
     * @throws \InvalidArgumentException
     */
    public function handle(\Throwable $throwable)
    {
        try {
            $response = \bean(ErrorHandlerChain::class)->walk(function ($handler) use ($throwable) {
                list($className, $method) = $handler;
                $method = ($className && class_exists($className)) ? $method : 'handle';
                if (is_string($className) && App::hasBean($className)) {
                    $class = App::getBean($className);
                } else {
                    $class = $className;
                }
                return PhpHelper::call([$class, $method], $this->getBindParams($className, $method, $throwable));
            });
        } catch (\Throwable $t) {
            /**
             * Do nothing, delegate to finally block,
             * and should not handle the excpetion throwed by ErrorHandler.
             */
        } finally {
            if (! $response) {
                $response = RequestContext::getResponse()->auto(ThrowableHelper::toArray($throwable));
            }
        }
        return $response;
    }

    /**
     * Get bind params
     *
     * @param string $className `
     * @param string $methodName
     * @param \Throwable $throwable
     * @return array
     * @throws \ReflectionException
     */
    private function getBindParams(string $className, string $methodName, \Throwable $throwable): array
    {
        $reflectClass = new \ReflectionClass($className);
        $reflectMethod = $reflectClass->getMethod($methodName);
        $reflectParams = $reflectMethod->getParameters();

        // binding params
        $bindParams = [];
        foreach ($reflectParams as $key => $reflectParam) {
            $reflectType = $reflectParam->getType();

            // undefined type of the param
            if ($reflectType === null) {
                $bindParams[$key] = null;
                continue;
            }

            /**
             * defined type of the param
             * @notice \ReflectType::getName() is not supported in PHP 7.0, that is why use __toString()
             */
            $type = $reflectType->__toString();
            if ($type === Request::class) {
                $bindParams[$key] = RequestContext::getRequest();
            } elseif ($type === Response::class) {
                $bindParams[$key] = RequestContext::getResponse();
            } elseif ($type === \Throwable::class) {
                $bindParams[$key] = $throwable;
            } else {
                $bindParams[$key] = null;
            }
        }

        return $bindParams;
    }

}