<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Server\ServiceServerEvent;
use Swoft\Server\Contract\ConnectInterface;
use Swoole\Server;

/**
 * Class ConnectListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class ConnectListener implements ConnectInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     */
    public function onConnect(Server $server, int $fd, int $reactorId): void
    {
        // Before connect
        Swoft::trigger(ServiceServerEvent::BEFORE_CONNECT, null, $server, $fd, $reactorId);

        // Connect event
        Swoft::trigger(ServiceServerEvent::CONNECT);

        // After connect
        Swoft::trigger(ServiceServerEvent::AFTER_CONNECT);
    }
}
