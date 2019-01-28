<?php declare(strict_types=1);


namespace Swoft\Http\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\DispatcherInterface;

/**
 * Class HttpDispatcher
 *
 * @Bean("httpDispatcher")
 * @since 2.0
 */
class HttpDispatcher implements DispatcherInterface
{
    /**
     * Dispatch http
     *
     * @param array ...$params
     */
    public function dispatch(...$params)
    {
        /**
         * @var Request  $request
         * @var Response $response
         */
        list($request, $response) = $params;

        $response->withContent("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>")->send();
    }

    /**
     * @return array
     */
    public function requestMiddleware(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function preMiddleware(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function afterMiddleware(): array
    {
        return [];
    }

}