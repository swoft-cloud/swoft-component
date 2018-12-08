<?php

namespace SwoftTest\ErrorHandler\Cases;

use PHPUnit\Framework\TestCase;
use Swoft\Bean\Collector\ExceptionHandlerCollector;
use Swoft\Core\RequestContext;
use Swoft\ErrorHandler\ErrorHandlerChain;
use Swoft\Http\Message\Server\Response;
use Swoft\Testing\SwooleResponse;

class AbstractTestCase extends TestCase
{
    public function setUp()
    {
        $chain = bean(ErrorHandlerChain::class);
        $chain->clear();

        $collector = ExceptionHandlerCollector::getCollector();
        foreach ($collector as $exception => list($class, $method)) {
            $priority = $handler[2] ?? 0;
            $chain->addHandler([$class, $method], $exception, $priority);
        }

        $swooleResponse = new SwooleResponse();
        $response = new Response($swooleResponse);
        RequestContext::setResponse($response);
    }
}