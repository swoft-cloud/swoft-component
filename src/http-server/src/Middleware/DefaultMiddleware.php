<?php declare(strict_types=1);

namespace Swoft\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Container;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Http\Server\Exception\MethodNotAllowedException;
use Swoft\Http\Server\Exception\NotFoundRouteException;
use Swoft\Http\Server\Formatter\AcceptResponseFormatter;
use Swoft\Http\Server\Router\Route;
use Swoft\Http\Server\Router\Router;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Stdlib\Helper\PhpHelper;

/**
 * Class DefaultMiddleware
 *
 * @Bean()
 * @since 2.0
 */
class DefaultMiddleware implements MiddlewareInterface
{
    /**
     * Accept formatter
     *
     * @var AcceptResponseFormatter
     * @Inject()
     */
    private $acceptFormatter;

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws \ReflectionException
     * @throws NotFoundRouteException
     * @throws MethodNotAllowedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->handle($request);
        $response = $this->acceptFormatter->format($response);
        return $response;
    }

    /**
     * @param ServerRequestInterface|Request $request
     *
     * @return Response
     * @throws \ReflectionException
     * @throws NotFoundRouteException
     * @throws MethodNotAllowedException
     */
    private function handle(ServerRequestInterface $request): Response
    {
        $method  = $request->getMethod();
        $uriPath = $request->getUriPath();

        /** @var Router $router */
        $router = Container::$instance->getSingleton('httpRouter');

        /* @var Route $route */
        [$status, , $route] = $router->match($uriPath, $method);

        // Not found route
        if ($status === Router::NOT_FOUND) {
            throw new NotFoundRouteException(\sprintf('Router(%s) not founded!', $uriPath));
        }

        // Method not allowed
        if ($status === Router::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowedException(\sprintf('Uri(%s) method(%s) not allowed!', $uriPath, $method));
        }

        // Controller and method
        [$className, $method] = \explode('@', $route->getHandler());

        $pathParams = $route->getParams();
        $bindParams = $this->bindParams($className, $method, $pathParams);
        $controller = Container::$instance->getSingleton($className);

        // Call class method
        $data = PhpHelper::call([$controller, $method], ...$bindParams);

        // Return is instanceof `ResponseInterface`
        if ($data instanceof ResponseInterface) {
            return $data;
        }

        $response = \context()->getResponse();
        return $response->withData($data);
    }

    /**
     * Bind params
     *
     * @param string $className
     * @param string $method
     * @param array  $pathParams
     *
     * @return array
     * @throws \ReflectionException
     */
    private function bindParams(string $className, string $method, array $pathParams): array
    {
        $reflection   = \Swoft::getReflection($className);
        $methodParams = $reflection['methods'][$method]['params'] ?? [];
        if (!$methodParams) {
            return [];
        }

        $bindParams = [];
        foreach ($methodParams as $methodParam) {
            [$paramName, $paramType, $paramDefaultType] = $methodParam;
            if (!$paramType instanceof \ReflectionNamedType) {
                continue;
            }

            $type = $paramType->getName();
            if ($type === Request::class) {
                $bindParams[] = context()->getRequest();
            } elseif ($type === Response::class) {
                $bindParams[] = context()->getResponse();
            } elseif (isset($pathParams[$paramName])) {
                $bindParams[] = ObjectHelper::parseParamType($type, $pathParams[$paramName]);
            } else {
                $bindParams[] = $paramDefaultType ?? ObjectHelper::getDefaultValue($type);
            }
        }

        return $bindParams;
    }
}