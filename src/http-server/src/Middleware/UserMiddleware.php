<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Http\Server\Exception\HttpServerException;
use Swoft\Http\Server\RequestHandler;
use Swoft\Http\Server\Router\Route;
use Swoft\Http\Server\Router\Router;
use function explode;

/**
 * Class UserMiddleware
 *
 * @Bean()
 *
 * @since 2.0
 */
class UserMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws HttpServerException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Route data
        $routeData = $request->getAttribute(Request::ROUTER_ATTRIBUTE);

        /* @var Route $route */
        [$status, , $route] = $routeData;

        if ($status !== Router::FOUND) {
            return $handler->handle($request);
        }

        // Controller and method
        $handlerId = $route->getHandler();
        [$className, $method] = explode('@', $handlerId);

        $middlewares = MiddlewareRegister::getMiddlewares($className, $method);
        if (!empty($middlewares) && $handler instanceof RequestHandler) {
            $handler->insertMiddlewares($middlewares);
        }

        return $handler->handle($request);
    }
}
