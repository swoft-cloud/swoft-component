<?php

namespace Swoft\ErrorHandler\Bootstrap\Listener;


use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bean\Collector\ExceptionHandlerCollector;
use Swoft\Bootstrap\Listeners\Interfaces\WorkerStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoole\Server;

/**
 * Class WorkerStartListener
 *
 * @package Swoft\ErrorHandler\Bootstrap\Listener
 * @ServerListener(SwooleEvent::ON_WORKER_START)
 */
class WorkerStartListener implements WorkerStartInterface
{

    public function onWorkerStart(Server $server, int $workerId, bool $isWorker)
    {
        if ($isWorker) {
            // Bean scan completed
            // Register Error Handler Chain
            $collector = ExceptionHandlerCollector::getCollector();
        }
    }
}
