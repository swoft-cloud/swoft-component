<?php

namespace Swoft\Http\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Exception\SwoftException;
use Swoft\Http\Message\Request as ServerRequest;
use Swoft\Http\Message\Response as ServerResponse;
use Swoft\Http\Server\HttpDispatcher;
use Swoft\Server\Contract\RequestInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class RequestListener
 *
 * @Bean()
 *
 * @since 2.0
 */
class RequestListener implements RequestInterface
{
    /**
     * @Inject()
     *
     * @var HttpDispatcher
     */
    private $dispatcher;

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws SwoftException
     */
    public function onRequest(Request $request, Response $response): void
    {
        $psrRequest  = ServerRequest::new($request);
        $psrResponse = ServerResponse::new($response);

        $this->dispatcher->dispatch($psrRequest, $psrResponse);
    }
}
