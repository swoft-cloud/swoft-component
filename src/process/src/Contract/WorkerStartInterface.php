<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Contract;

use Swoole\Process\Pool;

/**
 * Class WorkerStartInterface
 *
 * @since 2.0
 */
interface WorkerStartInterface
{
    /**
     * @param Pool $pool
     * @param int  $workerId
     */
    public function onWorkerStart(Pool $pool, int $workerId): void;
}
