<?php
namespace SwoftTest\Testing\Controllers;

use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use Swoft\Http\Server\Bean\Annotation\Controller;
use SwoftTest\Middlewares\ClassThirdMiddleware;
use SwoftTest\Middlewares\ClassSecondMiddleware;
use SwoftTest\Middlewares\ClassFirstMiddleware;
use SwoftTest\Middlewares\FirstMiddleware;
use SwoftTest\Middlewares\SecondMiddleware;
use SwoftTest\Middlewares\ThirdMiddleware;
use SwoftTest\Middlewares\FourthMiddleware;

/**
 * Class ValidatorController
 * @Controller(prefix="/middleware")
 * @Middleware(ClassFirstMiddleware::class)
 * @Middlewares({
 *     @Middleware(ClassSecondMiddleware::class),
 *     @Middleware(ClassThirdMiddleware::class)
 * })
 */
class MiddlewareController
{
    /**
     * @Middleware(FirstMiddleware::class)
     * @Middleware(SecondMiddleware::class)
     * @Middlewares({
     *     @Middleware(ThirdMiddleware::class),
     *     @Middleware(FourthMiddleware::class)
     * })
     */
    public function index()
    {
    }
}