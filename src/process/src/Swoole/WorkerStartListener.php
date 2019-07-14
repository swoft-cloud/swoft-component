<?php declare(strict_types=1);


namespace Swoft\Process\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Process\Contract\WorkerStartInterface;
use Swoft\Process\ProcessPool;
use Swoft\Stdlib\Helper\Dir;
use Swoft\Stdlib\Helper\Sys;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class WorkerStartListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class WorkerStartListener implements WorkerStartInterface
{
    /**
     * @param Pool $pool
     * @param int  $workerId
     */
    public function onWorkerStart(Pool $pool, int $workerId): void
    {
        // Init
        ProcessPool::$processPool->initProcessPool($pool);

        while (true) {
            Coroutine::sleep(3);

            var_dump('worker');
        }
    }
}