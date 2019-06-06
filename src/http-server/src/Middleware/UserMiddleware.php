<?php declare(strict_types=1);

namespace Swoft\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Http\Server\Exception\HttpServerException;
use Swoft\Http\Server\RequestHandler;
use Swoft\Http\Server\Router\Route;
use Swoft\Http\Server\Router\Router;
use function context;
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
     * @throws ContainerException
     * @throws HttpServerException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Request $request $method */
        $method  = $request->getMethod();
        $uriPath = $request->getUriPath();

        /** @var Router $router */
        $router    = Container::$instance->getSingleton('httpRouter');
        $routeData = $router->match($uriPath, $method);

        // Save matched route data to context
        context()->set(Request::ROUTER_ATTRIBUTE, $routeData);

        // Notice: will remove it, please use context()->get(Request::ROUTER_ATTRIBUTE);
        $request = $request->withAttribute(Request::ROUTER_ATTRIBUTE, $routeData);

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
