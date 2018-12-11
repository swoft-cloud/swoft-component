<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * service handler adapter
 *
 * @Bean
 */
class HandlerAdapterMiddleware implements MiddlewareInterface
{
    /**
     * execute service with handler
     *
     * @param \Psr\Http\Message\ServerRequestInterface     $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $serviceHandler = $request->getAttribute(RouterMiddleware::ATTRIBUTE);

        /* @var \Swoft\Rpc\Server\Router\HandlerAdapter $handlerAdapter */
        $handlerAdapter = App::getBean('serviceHandlerAdapter');

        return $handlerAdapter->doHandler($request, $serviceHandler);
    }
}
