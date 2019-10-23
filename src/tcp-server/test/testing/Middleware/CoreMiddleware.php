<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Testing\Middleware;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Tcp\Server\Contract\MiddlewareInterface;
use Swoft\Tcp\Server\Contract\RequestHandlerInterface;
use Swoft\Tcp\Server\Contract\RequestInterface;
use Swoft\Tcp\Server\Contract\ResponseInterface;
use Swoft\Tcp\Server\Response;

/**
 * Class CoreMiddleware
 *
 * @Bean()
 */
class CoreMiddleware implements MiddlewareInterface
{
    /**
     * @param RequestInterface        $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resp = Response::new(100);
        $resp->setData('[CORE]');

        return $resp;
    }
}
