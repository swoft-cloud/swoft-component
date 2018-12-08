<?php
/**
 * This file is part of Know.
 *
 * @link     https://code.aliyun.com/ky_tech/swoft-parent.git
 * @author   知我探索 开发组
 */

namespace SwoftTest\ErrorHandler\Testing;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Http\Message\Server\Response;
use SwoftTest\ErrorHandler\Testing\Exceptions\ExampleException;
use SwoftTest\ErrorHandler\Testing\Exceptions\ParamsInvalidException;

/**
 * the handler of http server exception
 * @ExceptionHandler()
 */
class ExampleExceptionHandler
{
    /**
     * @Handler(ExampleException::class)
     * @return Response
     */
    public function handleExampleException(Response $response, \Throwable $throwable)
    {
        $file = $throwable->getFile();
        $line = $throwable->getLine();
        $code = $throwable->getCode();
        $exception = $throwable->getMessage();

        return $response->json([
            'code' => $code,
            'exception' => ExampleException::class,
            'message' => $exception
        ]);
    }

    /**
     * @Handler(ParamsInvalidException::class)
     * @return Response
     */
    public function handleParamsInvalidException(Response $response, \Throwable $throwable)
    {
        $file = $throwable->getFile();
        $line = $throwable->getLine();
        $code = $throwable->getCode();
        $exception = $throwable->getMessage();

        return $response->json([
            'code' => $code,
            'exception' => ParamsInvalidException::class,
            'message' => $exception
        ]);
    }
}
