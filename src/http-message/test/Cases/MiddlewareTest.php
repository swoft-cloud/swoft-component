<?php

namespace SwoftTest\HttpMessage;

use SwoftTest\Testing\Controllers\MiddlewareController;

class MiddlewareTest extends AbstractTestCase
{
    public function testMiddleware()
    {
        $collector = $this->getMiddleware(MiddlewareController::class, "index");

        $this->assertEquals($collector, [
            \SwoftTest\Middlewares\ClassFirstMiddleware::class,
            \SwoftTest\Middlewares\ClassSecondMiddleware::class,
            \SwoftTest\Middlewares\ClassThirdMiddleware::class,
            \SwoftTest\Middlewares\FirstMiddleware::class,
            \SwoftTest\Middlewares\SecondMiddleware::class,
            \SwoftTest\Middlewares\ThirdMiddleware::class,
            \SwoftTest\Middlewares\FourthMiddleware::class,
        ]);
    }
}