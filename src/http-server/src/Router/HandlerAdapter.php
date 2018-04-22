<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Http\Server\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Core\RequestContext;
use Swoft\Exception\InvalidArgumentException;
use Swoft\Helper\PhpHelper;
use Swoft\Helper\StringHelper;
use Swoft\Http\Message\Router\HandlerAdapterInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Server\AttributeEnum;
use Swoft\Http\Server\Exception\MethodNotAllowedException;
use Swoft\Http\Server\Exception\RouteNotFoundException;
use Swoft\Http\Server\Payload;

/**
 * HTTP handler adapter
 * @Bean("httpHandlerAdapter")
 */
class HandlerAdapter implements HandlerAdapterInterface
{
    /**
     * Execute handler with controller and action
     *
     * @param ServerRequestInterface $request   request object
     * @param array                  $routeInfo handler info
     * @return Response
     * @throws \Swoft\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \Swoft\Http\Server\Exception\MethodNotAllowedException
     * @throws \Swoft\Http\Server\Exception\RouteNotFoundException
     * @throws \ReflectionException
     */
    public function doHandler(ServerRequestInterface $request, array $routeInfo): ResponseInterface
    {
        /**
         * @var int    $status
         * @var string $path
         * @var array  $info
         */
        list($status, $path, $info) = $routeInfo;

        // not founded route
        if ($status === HandlerMapping::NOT_FOUND) {
            throw new RouteNotFoundException('Route not found for ' . $path);
        }

        // method not allowed
        if ($status === HandlerMapping::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowedException(sprintf("Method '%s' not allowed for access %s, Allow: %s", $request->getMethod(), $path, \implode(',', $routeInfo[2])));
        }

        // handler info
        list($handler, $matches) = $this->createHandler($path, $info);

        // execute handler
        $params = $this->bindParams($request, $handler, $matches);
        $response = PhpHelper::call($handler, $params);

        // response
        if (! $response instanceof Response) {
            /* @var Response $newResponse */
            $newResponse = RequestContext::getResponse();

            // if is Payload
            if ($response instanceof Payload) {
                $response = $newResponse->withStatus($response->getStatus())
                                        ->withAttribute(AttributeEnum::RESPONSE_ATTRIBUTE, $response->data);
            } else {
                $response = $newResponse->withAttribute(AttributeEnum::RESPONSE_ATTRIBUTE, $response);
            }
        }

        return $response;
    }

    /**
     * create handler
     *
     * @param string $path url path
     * @param array  $info path info
     * @return array
     * @throws \InvalidArgumentException
     */
    public function createHandler(string $path, array $info): array
    {
        $handler = $info['handler'];
        $matches = $info['matches'] ?? [];

        // is a \Closure or a callable object
        if (\is_object($handler)) {
            return [$handler, $matches];
        }

        // is array ['controller', 'action']
        if (\is_array($handler)) {
            $segments = $handler;
        } elseif (\is_string($handler)) {
            // e.g `Controllers\Home@index` Or only `Controllers\Home`
            $segments = \explode('@', trim($handler));
        } else {
            App::error('Invalid route handler for URI: ' . $path);
            throw new \InvalidArgumentException('Invalid route handler for URI: ' . $path);
        }

        $action = '';
        $className = $segments[0];
        if (isset($segments[1])) {
            // Already assign action
            $action = $segments[1];
        } elseif (isset($matches[0])) {
            // use dynamic action
            $action = \array_shift($matches);
        }

        // use default action
        if (!$action) {
            /** @var HandlerMapping $httpRouter */
            $httpRouter = App::getBean('httpRouter');
            $action = $httpRouter->defaultAction;
        }

        $action     = StringHelper::camel($action);
        $controller = \bean($className);

        if (!\method_exists($controller, $action)) {
            throw new InvalidArgumentException("The controller action method '$action' does not exist!");
        }

        $handler = [$controller, $action];

        // Set Controller and Action info to Request Context
        RequestContext::setContextData([
            'controllerClass'  => $className,
            'controllerAction' => $action,
        ]);

        return [$handler, $matches];
    }

    /**
     * default handler
     *
     * @param array $handler handler info
     * @return array
     * @throws \Swoft\Exception\InvalidArgumentException
     */
    private function defaultHandler(array $handler): array
    {
        list($controller, $action) = $handler;
        $httpRouter = \bean('httpRouter');

        $action = empty($action) ? $httpRouter->defaultAction : $action;
        if (! method_exists($controller, $action)) {
            throw new InvalidArgumentException(sprintf('Action %s::%s() not exist', $controller, $action));
        }

        return [$controller, $action];
    }

    /**
     * binding params of action method
     *
     * @param ServerRequestInterface $request request object
     * @param mixed                  $handler handler
     * @param array                  $matches route params info
     * @return array
     * @throws \ReflectionException
     */
    private function bindParams(ServerRequestInterface $request, $handler, array $matches): array
    {
        if (\is_array($handler)) {
            list($controller, $method) = $handler;
            $reflectMethod = new \ReflectionMethod($controller, $method);
            $reflectParams = $reflectMethod->getParameters();
        } else {
            $reflectMethod = new \ReflectionFunction($handler);
            $reflectParams = $reflectMethod->getParameters();
        }

        $bindParams = [];
        $request    = $request->withAttribute(AttributeEnum::ROUTER_PARAMS, $matches);
        $response   = RequestContext::getResponse();

        // binding params
        foreach ($reflectParams as $key => $reflectParam) {
            $reflectType = $reflectParam->getType();
            $name = $reflectParam->getName();

            // undefined type of the param
            if ($reflectType === null) {
                if (isset($matches[$name])) {
                    $bindParams[$key] = $matches[$name];
                } else {
                    $bindParams[$key] = null;
                }
                continue;
            }

            /**
             * defined type of the param
             * @notice \ReflectType::getName() is not supported in PHP 7.0, that is why use __toString()
             */
            $type = $reflectType->__toString();
            if ($type === Request::class) {
                $bindParams[$key] = $request;
            } elseif ($type === Response::class) {
                $bindParams[$key] = $response;
            } elseif (isset($matches[$name])) {
                $bindParams[$key] = $this->parserParamType($type, $matches[$name]);
            } else {
                $bindParams[$key] = $this->getDefaultValue($type);
            }
        }

        return $bindParams;
    }

    /**
     * parser the type of binding param
     *
     * @param string $type  the type of param
     * @param mixed  $value the value of param
     * @return bool|float|int|string
     */
    private function parserParamType(string $type, $value)
    {
        switch ($type) {
            case 'int':
                $value = (int)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
                $value = (bool)$value;
                break;
            case 'float':
                $value = (float)$value;
                break;
            case 'double':
                $value = (double)$value;
                break;
        }

        return $value;
    }

    /**
     * the default value of param
     *
     * @param string $type the type of param
     * @return bool|float|int|string
     */
    private function getDefaultValue(string $type)
    {
        $value = null;
        switch ($type) {
            case 'int':
                $value = 0;
                break;
            case 'string':
                $value = '';
                break;
            case 'bool':
                $value = false;
                break;
            case 'float':
                $value = 0;
                break;
            case 'double':
                $value = 0;
                break;
        }

        return $value;
    }
}
