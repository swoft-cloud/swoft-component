<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\ErrorHandler\Testing;

use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Http\Message\Server\Response;
use SwoftTest\ErrorHandler\Testing\Exceptions\ExampleException;
use SwoftTest\ErrorHandler\Testing\Exceptions\ParamsInvalidException;

/**
 * the handler of http server exception
 * @ExceptionHandler
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
