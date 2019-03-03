<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

/**
 * Class WsServerEvent
 * @since 2.0
 */
final class WsServerEvent
{
    public const BEFORE_REGISTER_ROUTE = 'swoft.ws.server.router.register.before';
    public const BEFORE_HANDSHAKE      = 'swoft.ws.server.handshake';
    public const SUCCESS_HANDSHAKE     = 'swoft.ws.server.handshake.ok';
    public const AFTER_OPEN            = 'swoft.ws.server.open.after';
    public const BEFORE_MESSAGE        = 'swoft.ws.server.message.before';
    public const AFTER_MESSAGE         = 'swoft.ws.server.message.after';
    public const AFTER_CLOSE           = 'swoft.ws.server.close.after';
    public const ON_ERROR              = 'swoft.ws.server.error';
}
