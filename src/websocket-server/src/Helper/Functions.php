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

if (!function_exists('ws')) {
    /**
     * @return \Swoft\WebSocket\Server\WebSocketServer
     */
    function ws(): \Swoft\WebSocket\Server\WebSocketServer
    {
        return \Swoft\App::$server;
    }
}
