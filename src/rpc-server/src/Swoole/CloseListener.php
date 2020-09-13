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
use Swoft\Server\Contract\CloseInterface;
use Swoole\Server;

/**
 * Class CloseListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class CloseListener implements CloseInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        // Before close
        Swoft::trigger(ServiceServerEvent::BEFORE_CLOSE, null, $server, $fd, $reactorId);

        // Close event
        Swoft::trigger(ServiceServerEvent::CLOSE);

        // After close
        Swoft::trigger(ServiceServerEvent::AFTER_CLOSE);
    }
}
