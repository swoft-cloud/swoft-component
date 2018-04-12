<?php

namespace Swoft\WebSocket\Server\Event;

/**
 * Class WsEvent
 * @package Swoft\WebSocket\Server\Event
 */
final class WsEvent
{
    const ON_HANDSHAKE = 'handshake';
    const ON_OPEN = 'open';
    const ON_MESSAGE = 'message';
    const ON_CLOSE = 'close';
}
