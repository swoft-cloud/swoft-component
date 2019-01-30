<?php declare(strict_types=1);


namespace Swoft\Http\Server\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class DefaultMiddleware
 *
 * @Bean()
 * @since 2.0
 */
class DefaultMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = context()->getResponse();

        return $response->withContent("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
    }
}