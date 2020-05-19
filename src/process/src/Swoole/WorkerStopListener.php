<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Swoole;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Process\Contract\WorkerStopInterface;
use Swoft\Process\ProcessEvent;
use Swoole\Process\Pool;

/**
 * Class WorkerStopListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class WorkerStopListener implements WorkerStopInterface
{
    /**
     * @param Pool $pool
     * @param int  $workerId
     */
    public function onWorkerStop(Pool $pool, int $workerId): void
    {
        // file_put_contents('t.txt', 'stop');
        Swoft::trigger(ProcessEvent::BEFORE_PROCESS_STOP, $pool, $workerId);
    }
}
