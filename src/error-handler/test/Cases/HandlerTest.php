<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\ErrorHandler\Cases;

use Swoft\Bean\Collector\ExceptionHandlerCollector;
use Swoft\ErrorHandler\ErrorHandler;
use Swoft\Helper\JsonHelper;
use Swoft\Http\Message\Server\Response;
use SwoftTest\ErrorHandler\Testing\Exceptions\ExampleException;
use SwoftTest\ErrorHandler\Testing\Exceptions\ParamsInvalidException;

class HandlerTest extends AbstractTestCase
{
    public function testHandleCollection()
    {
        $col = ExceptionHandlerCollector::getCollector();

        $this->assertArrayHasKey(ExampleException::class, $col);
        $this->assertArrayHasKey(ParamsInvalidException::class, $col);
    }

    public function testHandleException()
    {
        $code = 1111;
        $msg = '测试异常';
        $ex = new ExampleException($msg, $code);

        $handler = bean(ErrorHandler::class);
        /** @var Response $response */
        $response = $handler->handle($ex);

        $res = JsonHelper::decode($response->getBody()->getContents(), true);

        $this->assertSame($code, $res['code']);
        $this->assertSame($msg, $res['message']);
        $this->assertSame(ExampleException::class, $res['exception']);
    }
}
