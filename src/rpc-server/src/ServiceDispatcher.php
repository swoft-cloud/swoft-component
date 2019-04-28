<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Dispatcher;
use Swoft\Log\Debug;
use Swoft\Rpc\Error;
use Swoft\Rpc\Server\Middleware\DefaultMiddleware;
use Swoft\Rpc\Server\Middleware\UserMiddleware;

/**
 * Class ServiceDispatcher
 *
 * @since 2.0
 *
 * @Bean(name="serviceDispatcher")
 */
class ServiceDispatcher extends Dispatcher
{
    /**
     * @var string
     */
    protected $defaultMiddleware = DefaultMiddleware::class;

    /**
     * @param array $params
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
            \Swoft::trigger(ServiceServerEvent::BEFORE_RECEIVE, null, $request, $response);

            $handler  = ServiceHandler::new($this->requestMiddleware(), $this->defaultMiddleware);
            $response = $handler->handle($request);
        } catch (\Throwable $e) {
            Debug::log(
                \sprintf("RPC Server Error: %s\nAt %s %d\n", $e->getMessage(), $e->getFile(), $e->getLine())
            );

            $error    = Error::new($e->getCode(), $e->getMessage(), null);
            $response = $response->setError($error);
        }

        \Swoft::trigger(ServiceServerEvent::AFTER_RECEIVE, null, $response);
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
            UserMiddleware::class
        ];
    }
}