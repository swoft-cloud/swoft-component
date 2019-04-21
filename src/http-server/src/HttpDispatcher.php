<?php declare(strict_types=1);

namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Dispatcher;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Middleware\DefaultMiddleware;
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
     * @throws \Swoft\Bean\Exception\ContainerException
     */

    /**
     * @param mixed ...$params
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function dispatch(...$params): void
    {
        /**
         * @var Request  $request
         * @var Response $response
         */
        [$request, $response] = $params;

        /* @var RequestHandler $requestHandler */
        $requestHandler = BeanFactory::getBean(RequestHandler::class);
        $requestHandler->initialize($this->requestMiddleware(), $this->defaultMiddleware);

        try {
            // Trigger before handle event
            \Swoft::trigger(HttpServerEvent::BEFORE_REQUEST, null, $request, $response);
            $response = $requestHandler->handle($request);
        } catch (\Throwable $e) {
            /** @var HttpErrorDispatcher $errDispatcher */
            $errDispatcher = BeanFactory::getSingleton(HttpErrorDispatcher::class);

            // Handle request error
            $response = $errDispatcher->run($e, $response);
        }

        // Trigger after request
        \Swoft::trigger(HttpServerEvent::AFTER_REQUEST, null, $response);
    }

    /**
     * @return array
     */
    public function preMiddleware(): array
    {
        return [
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
