<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Event;

/**
 * Class WsEvent
 * @package Swoft\WebSocket\Server\Event
 */
final class WsEvent
{
    const ON_HANDSHAKE = 'ws.handshake';

    const ON_OPEN = 'ws.open';

    const ON_MESSAGE = 'ws.message';

    const ON_CLOSE = 'ws.close';

    const ON_ERROR = 'ws.error';
}
