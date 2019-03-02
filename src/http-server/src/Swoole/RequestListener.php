<?php

namespace Swoft\Http\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\PrototypeException;
use Swoft\Http\Message\Request as ServerRequest;
use Swoft\Http\Message\Response as ServerResponse;
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
     * @throws PrototypeException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onRequest(Request $request, Response $response): void
    {
        // $response->end("<h1>Hello origin Swoole. #".rand(1000, 9999)."</h1>");

        $psrRequest  = ServerRequest::new($request);
        $psrResponse = ServerResponse::new($response);

        /* @var HttpDispatcher $httpDispatcher */
        $httpDispatcher = \bean('httpDispatcher');
        $httpDispatcher->dispatch($psrRequest, $psrResponse);
    }
}