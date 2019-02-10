<?php

namespace Swoft\WebSocket\Server;

/**
 * Class WsEvent
 * @package Swoft\WebSocket\Server
 */
final class WsEvent
{
    public const ON_HANDSHAKE    = 'ws.server.handshake';
    public const ON_HANDSHAKE_OK = 'ws.server.handshakeOk';
    public const ON_OPEN         = 'ws.server.open';
    public const ON_MESSAGE      = 'ws.server.message';
    public const ON_CLOSE        = 'ws.server.close';
    public const ON_ERROR        = 'ws.server.error';
}
