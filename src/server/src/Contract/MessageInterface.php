<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Contract;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class MessageInterface
 *
 * @since 2.0
 */
interface MessageInterface
{
    /**
     * Message event
     *
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame): void;
}
