<?php

namespace Swoft\Http\Server;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Swoft\App;
use Swoft\Contract\DispatcherInterface;
use Swoft\Core\RequestContext;
use Swoft\Core\RequestHandler;
use Swoft\ErrorHandler\ErrorHandler;
use Swoft\Event\AppEvent;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Server\Event\HttpServerEvent;
use Swoft\Http\Server\Middleware\HandlerAdapterMiddleware;
use Swoft\Http\Server\Middleware\SwoftMiddleware;
use Swoft\Http\Server\Middleware\UserMiddleware;
use Swoft\Http\Server\Middleware\ValidatorMiddleware;

/**
 * The dispatcher of http server
 */
class ServerDispatcher implements DispatcherInterface
{
    /**
     * User defined middlewares
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * Handler adapter
     *
     * @var string
     */
    protected $handlerAdapter = HandlerAdapterMiddleware::class;

    /**
     * Do dispatcher
     *
     * @param array ...$params
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function dispatch(...$params): ResponseInterface
    {
        try {
            /**
             * @var RequestInterface $request
             * @var ResponseInterface $response
             */
            list($request, $response) = $params;

            // Before server dispatch
            $this->beforeDispatch($request, $response);

            // Request middlewares
            $middlewares = $this->requestMiddleware();
            $request = RequestContext::getRequest();
            $requestHandler = new RequestHandler($middlewares, $this->handlerAdapter);
            $response = $requestHandler->handle($request);
        } catch (\Throwable $throwable) {
            $response = \bean(ErrorHandler::class)->handle($throwable);
        }

        // After server dispatch
        $this->afterDispatch($response);

        return $response;
    }

    /**
     * @param string $middleware
     * @param string|null $name
     */
    public function addMiddleware(string $middleware, string $name = null)
    {
        // Set middleware name to override if existed
        if ($name) {
            $this->middlewares[$name] = $middleware;
        } else {
            $this->middlewares[] = $middleware;
        }
    }

    /**
     * The middleware of request
     *
     * @return array
     */
    public function requestMiddleware(): array
    {
        return \array_merge($this->preMiddleware(), $this->middlewares, $this->afterMiddleware());
    }

    /**
     * Pre middleware
     *
     * @return array
     */
    public function preMiddleware(): array
    {
        return [
            SwoftMiddleware::class,
        ];
    }

    /**
     * After middleware
     *
     * @return array
     */
    public function afterMiddleware(): array
    {
        return [
            UserMiddleware::class,
            ValidatorMiddleware::class,
        ];
    }

    /**
     * before dispatcher
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws \InvalidArgumentException
     */
    protected function beforeDispatch(RequestInterface $request, ResponseInterface $response)
    {
        RequestContext::setRequest($request);
        RequestContext::setResponse($response);

        // Trigger 'Before Request' event
        App::trigger(HttpServerEvent::BEFORE_REQUEST);
    }

    /**
     * If $response is not an instance of Response,
     * usually return by Action of Controller,
     * then the auto() method will format the result
     * and return a suitable response
     *
     * @param mixed $response
     * @throws \InvalidArgumentException
     */
    protected function afterDispatch($response)
    {
        if (!$response instanceof Response) {
            $response = RequestContext::getResponse()->auto($response);
        }

        // Handle Response
        $response->send();

        // Release system resources
        App::trigger(AppEvent::RESOURCE_RELEASE);

        // Trigger 'After Request' event
        App::trigger(HttpServerEvent::AFTER_REQUEST);
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}
