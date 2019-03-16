<?php

namespace Swoft\Http\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
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
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onRequest(Request $request, Response $response): void
    {
        // $response->end('<h1>Hello Swoole. </h1>');
        // \Swoft::trigger('some.event');
        // return;

        $psrRequest  = ServerRequest::new($request);
        // return;
        $psrResponse = ServerResponse::new($response); // QPS: 2.3w

        /* @var HttpDispatcher $httpDispatcher */
        $httpDispatcher = Container::$instance->getSingleton('httpDispatcher');
        $httpDispatcher->dispatch($psrRequest, $psrResponse);
    }
}
