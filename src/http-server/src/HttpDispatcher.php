<?php declare(strict_types=1);

namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Dispatcher;
use Swoft\Http\Message\Response;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Middleware\DefaultMiddleware;
use Swoft\Http\Server\Middleware\RequestMiddleware;
use Swoft\Http\Server\Middleware\UserMiddleware;
use Swoft\Http\Server\Middleware\ValidatorMiddleware;

/**
 * Class HttpDispatcher
 *
 * @Bean("httpDispatcher")
 * @since 2.0
 */
class HttpDispatcher extends Dispatcher
{
    /**
     * Default middleware to handler request
     *
     * @var string
     */
    protected $defaultMiddleware = DefaultMiddleware::class;

    /**
     * Dispatch http request
     *
     * @param array ...$params
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function dispatch(...$params)
    {
        /**
         * @var Request  $request
         * @var Response $response
         */
        [$request, $response] = $params;

        try {

            // Trigger before event
            \Swoft::trigger(HttpServerEvent::BEFORE_REQUEST, $this, $request, $response);

            /* @var RequestHandler $requestHandler */
            $requestHandler = \bean(RequestHandler::class);
            $middlewares    = $this->requestMiddleware();

            $requestHandler->initialize($middlewares, $this->defaultMiddleware);
            $response = $requestHandler->handle($request);
        } catch (\Throwable $e) {
            var_dump($e->getMessage(), $e->getFile(), $e->getLine());
        }

        \Swoft::trigger(HttpServerEvent::AFTER_REQUEST, $this, $response);

//      $response->withContent("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>")->send();
    }

    /**
     * @return array
     */
    public function preMiddleware(): array
    {
        return [
            RequestMiddleware::class
        ];
    }

    /**
     * @return array
     */
    public function afterMiddleware(): array
    {
        return [
            UserMiddleware::class,
            ValidatorMiddleware::class
        ];
    }
}