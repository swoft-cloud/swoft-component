<?php
/**
 * This file is part of Know.
 *
 * @link     https://code.aliyun.com/ky_tech/swoft-parent.git
 * @author   知我探索 开发组
 */

namespace SwoftTest\Exception\Handlers;

use App\Core\Logger\ThrowableLogger;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Http\Message\Server\Response;
use App\Exception\HttpServerException;

/**
 * the handler of http server exception
 *
 * @ExceptionHandler()
 * @uses      Handler
 * @version   2018年01月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpServerExceptionHandler
{
    /**
     * 注入自定义Response
     * @Inject()
     *
     * @var \App\Core\HttpServer\Response
     */
    private $response;

    /**
     * @Inject
     * @var ThrowableLogger
     */
    private $logger;

    /**
     * @Handler(HttpServerException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerException(Response $response, \Throwable $throwable)
    {
        $file = $throwable->getFile();
        $line = $throwable->getLine();
        $code = $throwable->getCode();
        $exception = $throwable->getMessage();

        $this->logger->warning($throwable);

        return $this->response->fail($code, $exception);
    }
}
