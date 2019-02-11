<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-05
 * Time: 17:33
 */

namespace Swoft\WebSocket\Server\Contract;

use Swoft\WebSocket\Server\Connection;
use Swoole\Websocket\Frame;

/**
 * Interface MessageHandlerInterface
 * @package Swoft\WebSocket\Server\Contract
 */
interface MessageHandlerInterface
{
    /**
     * @param Frame      $frame
     * @param Connection $conn
     */
    public function handle(Frame $frame, Connection $conn): void;
}
