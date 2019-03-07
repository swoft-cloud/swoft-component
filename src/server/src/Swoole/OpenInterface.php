<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoft\Http\Message\Request;
use Swoole\Websocket\Server;

/**
 * Interface OpenInterface
 *
 * @since 2.0
 */
interface OpenInterface
{
    /**
     * Open event
     *
     * @param Server  $server
     * @param Request $request
     */
    public function onOpen(Server $server, Request $request): void;
}
