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
use Swoft\Bean\Annotation\Bean;
use Swoft\Core\RequestHandler;
use Swoft\Http\Message\Bean\Collector\MiddlewareCollector;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * the annotation middlewares of action
 *
 * @Bean
 * @uses      UserMiddleware
 * @version   2017年12月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface     $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        list($className, $funcName) = $request->getAttribute(RouterMiddleware::ATTRIBUTE);

        $middlewares         = [];
        $collector           = MiddlewareCollector::getCollector();
        $middlewareCollector = $collector[$className]['middlewares'] ?? [];
        $groupMiddlewares    = $middlewareCollector['group'] ?? [];
        $funcMiddlewares     = $middlewareCollector['actions'][$funcName] ?? [];

        $middlewares = \array_merge($middlewares, $groupMiddlewares, $funcMiddlewares);

        if (!empty($middlewares) && $handler instanceof RequestHandler) {
            $handler->insertMiddlewares($middlewares);
        }

        return $handler->handle($request);
    }
}
