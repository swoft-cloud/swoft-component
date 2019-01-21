<?php

namespace Swoft\Server\Swoole;

use Co\Http\Request;
use Co\Http\Response;

/**
 * Interface RequestInterface
 *
 * @since 2.0
 */
interface RequestInterface
{
    /**
     * Request event
     *
     * @param Request  $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response): void;
}