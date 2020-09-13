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

use Swoole\Server;

/**
 * Class ReceiveInterface
 *
 * @since 2.0
 */
interface ReceiveInterface
{
    /**
     * Receive event
     *
     * @param Server $server
     * @param int      $fd
     * @param int      $reactorId
     * @param string   $data
     */
    public function onReceive(Server $server, int $fd, int $reactorId, string $data): void;
}
