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
 * Class FinishInterface
 *
 * @since 2.0
 */
interface FinishInterface
{
    /**
     * Finish event
     *
     * @param Server $server
     * @param int      $taskId
     * @param string   $data
     */
    public function onFinish(Server $server, int $taskId, string $data): void;
}
