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
 * Class SyncTaskInterface
 *
 * @since 2.0
 */
interface SyncTaskInterface
{
    /**
     * Task event
     *
     * @param Server $server
     * @param int    $taskId
     * @param int    $srcWorkerId
     * @param mixed  $data
     *
     * @return mixed
     */
    public function onTask(Server $server, $taskId, int $srcWorkerId, $data);
}
