<?php declare(strict_types=1);


namespace Swoft\Process\Swoole;


use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Process\Contract\WorkerStartInterface;
use Swoft\Process\ProcessDispatcher;
use Swoft\Process\ProcessEvent;
use Swoft\Process\ProcessPool;
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
     * @Inject()
     *
     * @var ProcessDispatcher
     */
    private $processDispatcher;

    /**
     * @param Pool $pool
     * @param int  $workerId
     *
     */
    public function onWorkerStart(Pool $pool, int $workerId): void
    {
        // Init
        ProcessPool::$processPool->initProcessPool($pool);

        // Before
        Swoft::trigger(ProcessEvent::BEFORE_PROCESS_START, $this, $pool, $workerId);

        // Dispatcher
        $this->processDispatcher->dispatcher($pool, $workerId);

        // After
        Swoft::trigger(ProcessEvent::AFTER_PROCESS_START, $this, $pool, $workerId);
    }
}
