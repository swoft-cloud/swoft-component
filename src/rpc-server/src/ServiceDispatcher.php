<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Dispatcher;
use Swoft\Rpc\Server\Middleware\DefaultMiddleware;

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
     * @param array ...$params
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
            $response = $response->withContent('hello rpc');
        } catch (\Throwable $e) {
            echo json_encode($e);
            \printf(
                "HTTP Dispatch Error: %s\nAt %s %d\n",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
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

        ];
    }
}