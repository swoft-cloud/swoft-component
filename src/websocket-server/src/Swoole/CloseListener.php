<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 13:37
 */

namespace Swoft\WebSocket\Server\Swoole;


use Co\Server as CoServer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\CloseInterface;

/**
 * Class CloseListener
 * @since 2.0
 * @package Swoft\WebSocket\Server\Swoole
 *
 * @Bean("closeListener")
 */
class CloseListener implements CloseInterface
{
    /**
     * Close event
     *
     * @param CoServer $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onClose(CoServer $server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.
    }
}
