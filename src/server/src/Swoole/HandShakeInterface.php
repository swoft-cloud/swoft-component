<?php

namespace Swoft\Server\Swoole;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Interface HandShakeInterface
 *
 * @since 2.0
 */
interface HandShakeInterface
{
    /**
     * HandShake event
     *
     * @param Request  $request
     * @param Response $response
     * @return bool
     */
    public function onHandShake(Request $request, Response $response): bool;
}
