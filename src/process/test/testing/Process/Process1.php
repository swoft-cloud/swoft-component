<?php

namespace SwoftTest\Process\Testing\Process;

use Swoft\Log\Helper\CLog;
use Swoft\Process\Annotation\Mapping\Process;
use Swoft\Process\Contract\ProcessInterface;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class Process1
 *
 * @Process(workerNum=3)
 */
class Process1 implements ProcessInterface
{

    /**
     * @param Pool $pool
     * @param int $workerId
     */
    public function run(Pool $pool, int $workerId): void
    {
        while (true) {
            CLog::info('worker-' . $workerId);
            Coroutine::sleep(3);
        }
    }
}