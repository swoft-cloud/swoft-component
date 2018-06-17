<?php

namespace Swoft\Core;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Collector\ExceptionHandlerCollector;
use Swoft\Helper\PhpHelper;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;

/**
 * Error and Exception Handler
 * @Bean()
 */
class ErrorHandler
{

    /**
     * handle exception
     *
     * @param \Throwable $throwable
     * @return \Swoft\Http\Message\Server\Response|array|string
     */
    public function handle(\Throwable $throwable)
    {
        try {
            $exceptionClass = \get_class($throwable);
            $collector = ExceptionHandlerCollector::getCollector();
            $isNotExistHandler = ! isset($collector[$exceptionClass]) && ! isset($collector[\Exception::class]);
            if (empty($collector) || $isNotExistHandler) {
                throw $throwable;
            }

            if (isset($collector[$exceptionClass])) {
                list($classBeanName, $methodName) = $collector[$exceptionClass];
            } else {
                list($classBeanName, $methodName) = $collector[\Exception::class];
            }

            $handler = \bean($classBeanName);
            $bindParams = $this->getBindParams($classBeanName, $methodName, $throwable);
            $response = PhpHelper::call([$handler, $methodName], $bindParams);
        } catch (\Throwable $e) {
            $response = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
                'previous' => $e->getPrevious(),
            ];
        }

        return $response;
    }

    /**
     * handler throwable
     *
     * @param \Throwable $throwable
     * @return array
     */
    private function handleThrowtable(\Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTrace(),
            'previous' => $throwable->getPrevious(),
        ];
    }

    /**
     * get binded params
     *
     * @param string $className
     * @param string $methodName
     * @param \Throwable $throwable
     * @return array
     */
    private function getBindParams(string $className, string $methodName, \Throwable $throwable)
    {
        $reflectClass = new \ReflectionClass($className);
        $reflectMethod = $reflectClass->getMethod($methodName);
        $reflectParams = $reflectMethod->getParameters();
        $response = RequestContext::getResponse();
        $request = RequestContext::getRequest();

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
                $bindParams[$key] = $request;
            } elseif ($type === Response::class) {
                $bindParams[$key] = $response;
            } elseif ($type === \Throwable::class) {
                $bindParams[$key] = $throwable;
            } else {
                $bindParams[$key] = null;
            }
        }

        return $bindParams;
    }
}