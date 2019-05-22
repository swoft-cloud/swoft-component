<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

/**
 * Class WsServerEvent
 *
 * @since 2.0
 */
final class WsServerEvent
{
    public const REGISTER_ROUTE    = 'swoft.ws.server.router.register';
    public const HANDSHAKE_BEFORE  = 'swoft.ws.server.handshake.before';
    public const HANDSHAKE_SUCCESS = 'swoft.ws.server.handshake.ok';
    public const HANDSHAKE_ERROR   = 'swoft.ws.server.handshake.error';
    public const OPEN_AFTER        = 'swoft.ws.server.open.after';
    public const OPEN_ERROR        = 'swoft.ws.server.open.error';
    public const MESSAGE_BEFORE    = 'swoft.ws.server.message.before';
    public const MESSAGE_ERROR     = 'swoft.ws.server.message.error';
    public const MESSAGE_AFTER     = 'swoft.ws.server.message.after';
    public const AFTER_CLOSE       = 'swoft.ws.server.close.after';
    public const CLOSE_ERROR       = 'swoft.ws.server.close.error';
}
