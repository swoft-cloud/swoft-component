<?php

namespace Swoft\Http\Server\Swoole;


use Swoft\Http\Server\HttpDispatcher;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\RequestInterface;

/**
 * Class RequestListener
 *
 * @Bean("requestListener")
 *
 * @since 2.0
 */
class RequestListener implements RequestInterface
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onRequest(Request $request, Response $response): void
    {
//        $response->end("<h1>Hello origin Swoole. #".rand(1000, 9999)."</h1>");

        /* @var \Swoft\Http\Server\Request $psrRequest */
        $psrRequest = \bean(\Swoft\Http\Server\Request::class);
        $psrRequest->initialize($request);

        /* @var \Swoft\Http\Server\Response $psrResponse */
        $psrResponse = \bean(\Swoft\Http\Server\Response::class);
        $psrResponse->initialize($response);

        /* @var HttpDispatcher $httpDispatcher */
        $httpDispatcher = \bean('httpDispatcher');
        $httpDispatcher->dispatch($psrRequest, $psrResponse);
    }
}