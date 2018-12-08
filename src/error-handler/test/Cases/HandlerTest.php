<?php
/**
 * Created by PhpStorm.
 * User: limx
 * Date: 2018/12/8
 * Time: 1:58 PM
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

        $this->assertEquals($code, $res['code']);
        $this->assertEquals($msg, $res['message']);
        $this->assertEquals(ExampleException::class, $res['exception']);
    }
}