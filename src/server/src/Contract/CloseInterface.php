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
 * Class CloseInterface
 *
 * @since 2.0
 */
interface CloseInterface
{
    /**
     * Close event
     *
     * on connection closed
     * - you can do something. eg. record log
     *
     * @param Server $server
     * @param int      $fd
     * @param int      $reactorId
     */
    public function onClose(Server $server, int $fd, int $reactorId): void;
}
