<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server;

use ReflectionException;
use ReflectionType;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;
use Swoft\Tcp\ErrCode;
use Swoft\Tcp\Package;
use Swoft\Tcp\Protocol;
use Swoft\Tcp\Server\Contract\MiddlewareInterface;
use Swoft\Tcp\Server\Contract\RequestHandlerInterface;
use Swoft\Tcp\Server\Contract\RequestInterface;
use Swoft\Tcp\Server\Contract\ResponseInterface;
use Swoft\Tcp\Server\Exception\CommandNotFoundException;
use Swoft\Tcp\Server\Exception\TcpMiddlewareException;
use Swoft\Tcp\Server\Exception\TcpUnpackingException;
use Swoft\Tcp\Server\Router\Router;
use Throwable;
use function array_merge;
use function context;
use function server;
use function sprintf;

/**
 * Class TcpDispatcher
 *
 * @since 2.0.3
 * @Bean("tcpDispatcher")
 */
class TcpDispatcher implements MiddlewareInterface
{
    /**
     * Enable internal route dispatching
     *
     * @see \Swoft\Tcp\Server\Swoole\ReceiveListener::onReceive()
     * @var bool
     */
    private $enable = true;

    /**
     * Pre-check whether the route matches successfully.
     * True  - Check if the status matches successfully after matching.
     * False - check the status after the middleware process
     *
     * @var bool
     */
    private $preCheckRoute = true;

    /**
     * User defined global middlewares
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * User defined global pre-middlewares
     *
     * @var array
     */
    private $preMiddlewares = [];

    /**
     * User defined global after-middlewares
     *
     * @var array
     */
    private $afterMiddlewares = [];

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response|ResponseInterface
     * @throws TcpUnpackingException
     * @throws TcpMiddlewareException
     * @throws CommandNotFoundException
     * @throws ReflectionException
     */
    public function dispatch(Request $request, Response $response): ResponseInterface
    {
        /** @var Protocol $protocol */
        $protocol = Swoft::getBean(TcpServerBean::PROTOCOL);
        CLog::info('Tcp protocol data packer is %s', $protocol->getPackerClass());

        try {
            $package = $protocol->unpack($request->getRawData());
            $request->setPackage($package);
        } catch (Throwable $e) {
            $errMsg = sprintf('unpack request data error - %s', $e->getMessage());
            throw new TcpUnpackingException($errMsg, ErrCode::UNPACKING_FAIL, $e);
        }

        /** @var Router $router */
        $router  = Swoft::getSingleton(TcpServerBean::ROUTER);
        $command = $package->getCmd() ?: $router->getDefaultCommand();

        // Match command
        $result = $router->match($command);
        $status = $result[0];

        // Storage route info
        $request->set(Request::ROUTE_INFO, $result);

        // Found, get command middlewares
        $middlewares = [];
        if ($status === Router::FOUND) {
            $middlewares = $router->getCmdMiddlewares($command);

            // Append command middlewares
            if ($middlewares) {
                $middlewares = array_merge($this->middlewares, $middlewares);
            }

            // If this->preCheckRoute is True, pre-check route match status
        } elseif ($this->preCheckRoute) {
            $errMsg = sprintf("request command '%s' is not found of the tcp server", $command);
            throw new CommandNotFoundException($errMsg, ErrCode::ROUTE_NOT_FOUND);
        }

        // Has middlewares
        if ($middlewares = $this->mergeMiddlewares($middlewares)) {
            $chain = RequestHandler::new($this);
            $chain->addMiddles($middlewares);

            CLog::debug('request will use middleware process, middleware count: %d', $chain->count());

            return $chain->run($request);
        }

        // No middlewares, direct dispatching
        return $this->dispatchRequest($request, $response);
    }

    /**
     * For middleware dispatching
     *
     * @param RequestInterface|Request $request
     * @param RequestHandlerInterface  $handler
     *
     * @return ResponseInterface|Response
     * @throws CommandNotFoundException
     * @throws ReflectionException
     */
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Response $response */
        $response = context()->getResponse();

        return $this->dispatchRequest($request, $response);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return ResponseInterface
     * @throws CommandNotFoundException
     * @throws ReflectionException
     */
    protected function dispatchRequest(Request $request, Response $response): ResponseInterface
    {
        [$status, $info] = $request->get(Request::ROUTE_INFO);

        // If this->preCheckRoute is False, check route match status.
        $cmd = $info['command'];
        if (!$this->preCheckRoute && $status === Router::NOT_FOUND) {
            $errMsg = sprintf("request command '%s' is not found of the tcp server", $cmd);
            throw new CommandNotFoundException($errMsg, ErrCode::ROUTE_NOT_FOUND);
        }

        // Extract handler info
        [$class, $method] = $info['handler'];

        server()->log("Tcp command: '{$cmd}', will call tcp request handler {$class}@{$method}");

        // Find class bean object
        $object = Swoft::getBean($class);
        $params = $this->getBindParams($class, $method, $request, $response);
        $result = $object->$method(...$params);

        // If result is not empty
        if ($result && !$result instanceof Response) {
            $response->setData($result);
        }

        return $response;
    }

    /**
     * Get method bounded params
     *
     * @param string   $class
     * @param string   $method
     * @param Request  $r
     * @param Response $w
     *
     * @return array
     * @throws ReflectionException
     */
    private function getBindParams(string $class, string $method, Request $r, Response $w): array
    {
        $classInfo = Swoft::getReflection($class);
        if (!isset($classInfo['methods'][$method])) {
            return [];
        }

        // binding params
        $bindParams   = [];
        $methodParams = $classInfo['methods'][$method]['params'];

        /**
         * @var string         $name
         * @var ReflectionType $paramType
         * @var mixed          $devVal
         */
        foreach ($methodParams as [$name, $paramType, $devVal]) {
            // Defined type of the param
            $type = $paramType ? $paramType->getName() : '';

            if ($type === Package::class) {
                $bindParams[] = $r->getPackage();
            } elseif ($type === Request::class) {
                $bindParams[] = $r;
            } elseif ($type === Response::class) {
                $bindParams[] = $w;
            } else {
                $bindParams[] = null;
            }
        }

        return $bindParams;
    }

    /**
     * @return bool
     */
    public function isPreCheckRoute(): bool
    {
        return $this->preCheckRoute;
    }

    /**
     * @param bool $preCheckRoute
     */
    public function setPreCheckRoute(bool $preCheckRoute): void
    {
        $this->preCheckRoute = $preCheckRoute;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param string $middleware
     * @param string $name
     */
    public function addMiddleware(string $middleware, string $name = ''): void
    {
        if ($name) {
            $this->middlewares[$name] = $middleware;
        } else {
            $this->middlewares[] = $middleware;
        }
    }

    /**
     * @param array $middlewares
     */
    public function addMiddlewares(array $middlewares): void
    {
        if ($middlewares) {
            $this->middlewares = array_merge($this->middlewares, $middlewares);
        }
    }

    /**
     * @param array $middlewares
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @return array
     */
    public function getPreMiddlewares(): array
    {
        return $this->preMiddlewares;
    }

    /**
     * @param array $preMiddlewares
     */
    public function setPreMiddlewares(array $preMiddlewares): void
    {
        $this->preMiddlewares = $preMiddlewares;
    }

    /**
     * @return array
     */
    public function getAfterMiddlewares(): array
    {
        return $this->afterMiddlewares;
    }

    /**
     * @param array $afterMiddlewares
     */
    public function setAfterMiddlewares(array $afterMiddlewares): void
    {
        $this->afterMiddlewares = $afterMiddlewares;
    }

    /**
     * merge all middlewares
     *
     * @param array $middlewares
     *
     * @return array
     */
    protected function mergeMiddlewares(array $middlewares): array
    {
        if ($middlewares) {
            return array_merge($this->preMiddlewares, $middlewares, $this->afterMiddlewares);
        }

        return array_merge($this->preMiddlewares, $this->afterMiddlewares);
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable($enable): void
    {
        $this->enable = (bool)$enable;
    }
}
