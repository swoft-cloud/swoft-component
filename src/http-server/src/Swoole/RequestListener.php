<?php

namespace Swoft\Http\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ServerRequest;
use Swoft\Http\Server\HttpDispatcher;
use Swoft\Server\Swoole\RequestInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

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
        // $response->end("<h1>Hello origin Swoole. #".rand(1000, 9999)."</h1>");

        /* @var ServerRequest $psrRequest */
        $psrRequest = \bean(ServerRequest::class);
        $psrRequest->initialize($request);

        /* @var \Swoft\Http\Message\Response $psrResponse */
        $psrResponse = \bean(\Swoft\Http\Message\Response::class);
        $psrResponse->initialize($response);

        /* @var HttpDispatcher $httpDispatcher */
        $httpDispatcher = \bean('httpDispatcher');
        $httpDispatcher->dispatch($psrRequest, $psrResponse);
    }
}