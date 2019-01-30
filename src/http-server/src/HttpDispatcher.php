<?php declare(strict_types=1);


namespace Swoft\Http\Server;


use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Dispatcher;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Middleware\DefaultMiddleware;
use Swoft\Http\Server\Middleware\RequestMiddleware;

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
        list($request, $response) = $params;

        try {

            $this->before($request, $response);

            /* @var RequestHandler $requestHandler */
            $requestHandler = \bean(RequestHandler::class);
            $middlewares    = $this->requestMiddleware();

            $requestHandler->initialize($middlewares, $this->defaultMiddleware);
            $response = $requestHandler->handle($request);
        } catch (\Throwable $e) {
            var_dump($e->getMessage(), $e->getFile(), $e->getLine());
        }

        $this->after($response);

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

        ];
    }

    /**
     * @param mixed ...$params
     *
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public function before(...$params): void
    {
        list($request, $response) = $params;

        /* @var HttpContext $httpContext */
        $httpContext = \bean(HttpContext::class);
        $httpContext->initialize($request, $response);

        Context::set($httpContext);
    }

    /**
     * @param array ...$params
     *
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public function after(...$params): void
    {
        /* @var Response $response */
        list($response) = $params;

        $response->send();
        Context::destroy();
    }

}