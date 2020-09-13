<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Concern\AbstractDispatcher;
use Swoft\Context\Context;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Formatter\AcceptResponseFormatter;
use Swoft\Http\Server\Middleware\DefaultMiddleware;
use Swoft\Http\Server\Middleware\UserMiddleware;
use Swoft\Http\Server\Router\Router;
use Swoft\Log\Error;
use Swoft\Log\Logger;
use Swoft\Server\SwooleEvent;
use Swoft\SwoftEvent;
use Throwable;

/**
 * Class HttpDispatcher
 *
 * @Bean("httpDispatcher")
 * @since 2.0
 */
class HttpDispatcher extends AbstractDispatcher
{
    /**
     * Default middleware to handler request
     *
     * @var string
     */
    protected $defaultMiddleware = DefaultMiddleware::class;

    /**
     * Accept formatter
     *
     * @var AcceptResponseFormatter
     * @Inject()
     */
    protected $acceptFormatter;

    /**
     * @Inject("logger")
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Dispatch http request
     *
     * @param mixed ...$params
     */
    public function dispatch(...$params): void
    {
        /**
         * @var Request  $request
         * @var Response $response
         */
        [$request, $response] = $params;

        /* @var RequestHandler $requestHandler */
        $requestHandler = Swoft::getBean(RequestHandler::class);

        try {
            $requestHandler->initialize($this->requestMiddlewares, $this->defaultMiddleware);

            // Before request
            $this->beforeRequest($request, $response);

            // Trigger before handle event
            Swoft::trigger(HttpServerEvent::BEFORE_REQUEST, null, $request, $response);

            // Match router and handle
            $request  = $this->matchRouter($request);
            $response = $requestHandler->handle($request);
        } catch (Throwable $e) {
            /** @var HttpErrorDispatcher $errDispatcher */
            $errDispatcher = Swoft::getSingleton(HttpErrorDispatcher::class);

            // Handle request error
            $response = $errDispatcher->run($e, $response);
        }

        try {
            // Format response content type
            $response = $this->acceptFormatter->format($response);

            // Trigger after request
            Swoft::trigger(HttpServerEvent::AFTER_REQUEST, null, $response);

            // After request
            $this->afterRequest($response);
        } catch (Throwable $e) {
            Error::log('response error=%s(%d) at %s:%d', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
        }
    }

    /**
     * @return array
     */
    public function afterMiddleware(): array
    {
        return [
            UserMiddleware::class
        ];
    }

    /**
     * @param Request  $request
     * @param Response $response
     */
    private function beforeRequest(Request $request, Response $response): void
    {
        $httpContext = HttpContext::new($request, $response);

        // Add log data
        if ($this->logger->isEnable()) {
            $data = [
                'event'       => SwooleEvent::REQUEST,
                'uri'         => $request->getRequestTarget(),
                'requestTime' => $request->getRequestTime(),
            ];

            $httpContext->setMulti($data);
        }

        Context::set($httpContext);
    }

    /**
     * @param Response $response
     */
    private function afterRequest(Response $response): void
    {
        $response->send();

        // Defer
        Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

        // Destroy
        Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);
    }

    /**
     * @param Request $request
     *
     * @return Request
     */
    private function matchRouter(Request $request): Request
    {
        $method  = $request->getMethod();
        $uriPath = $request->getUriPath();

        /** @var Router $router */
        $router = Swoft::getSingleton('httpRouter');
        $result = $router->match($uriPath, $method);

        // Save matched route data to request
        $request = $request->withAttribute(Request::ROUTER_ATTRIBUTE, $result);
        context()->setRequest($request);

        return $request;
    }
}
