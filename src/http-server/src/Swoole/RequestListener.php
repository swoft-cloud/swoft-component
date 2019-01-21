<?php

namespace Swoft\Http\Server\Swoole;


use Co\Http\Request;
use Co\Http\Response;
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
     * Request event
     *
     * @param Request  $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response): void
    {

    }
}