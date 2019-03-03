<?php declare(strict_types=1);


namespace Swoft\Http\Server\Middleware;


use App\Controller\TestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Exception\MethodNotAllowedException;
use Swoft\Http\Server\Exception\NotFoundRouteException;
use Swoft\Http\Server\Formatter\AcceptResponseFormatter;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Router\Route;
use Swoft\Http\Server\Router\Router;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Table\Row;

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
     * @throws \Swoft\Bean\Exception\ContainerException
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
     * @param ServerRequestInterface $request
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws NotFoundRouteException
     * @throws MethodNotAllowedException
     */
    private function handle(ServerRequestInterface $request): Response
    {
        $uri    = $request->getUri()->getPath();
        $method = $request->getMethod();

        /** @var Router $router */
        $router = \bean('httpRouter');

        /* @var Route $route */
        [$status, , $route] = $router->match($uri, $method);

        // Not found route
        if ($status == Router::NOT_FOUND || empty($route) || empty($route->getHandler())) {
            throw new NotFoundRouteException(sprintf('Router(%s) not founded!', $uri));
        }

        // Method not allowed
        if ($status == Router::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowedException(sprintf('Uri(%s) method(%s) not allowed!', $uri, $method));
        }
        // Controller and method
        [$controllerClass, $method] = explode('@', $route->getHandler());

        $pathParams = $route->getParams();
        $bindParams = $this->bindParams($controllerClass, $method, $pathParams);
        $controller = \bean($controllerClass);

        // Call method
        $data = PhpHelper::call([$controller, $method], ...$bindParams);

        // Return is not `ResponseInterface`
        if ($data instanceof ResponseInterface) {
            return $data;
        }

        $response = context()->getResponse();
        return $response->withData($data);
    }

    /**
     * Bind params
     *
     * @param string $controllerClass
     * @param string $method
     * @param array  $pathParams
     *
     * @return array
     * @throws \ReflectionException
     */
    private function bindParams(string $controllerClass, string $method, array $pathParams): array
    {
        $reflection   = \Swoft::getReflection($controllerClass);
        $methodParams = $reflection['methods'][$method]['params'] ?? [];
        if (empty($methodParams)) {
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
                $bindParams[] = ($paramDefaultType === null) ? ObjectHelper::getDefaultValue($type) : $paramDefaultType;
            }
        }

        return $bindParams;
    }
}