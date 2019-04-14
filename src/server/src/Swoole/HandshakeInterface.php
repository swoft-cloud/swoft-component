<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Interface HandshakeInterface
 *
 * @since 2.0
 */
interface HandshakeInterface
{
    /**
     * Ws Handshake event
     *
     * @param Request  $request
     * @param Response $response
     * @return bool
     */
    public function onHandshake(Request $request, Response $response): bool;
}
