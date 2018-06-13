<?php

namespace Swoft\ErrorHandler\Bootstrap\Listener;


use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bean\Collector\ExceptionHandlerCollector;
use Swoft\Bootstrap\Listeners\Interfaces\WorkerStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Console\Helper\ConsoleUtil;
use Swoft\ErrorHandler\ErrorHandlerChain;
use Swoole\Server;

/**
 * Class WorkerStartListener
 * @ServerListener(SwooleEvent::ON_WORKER_START)
 */
class WorkerStartListener implements WorkerStartInterface
{

    public function onWorkerStart(Server $server, int $workerId, bool $isWorker)
    {
        if ($isWorker && $collector = ExceptionHandlerCollector::getCollector()) {
            $chain = \bean(ErrorHandlerChain::class);
            foreach ($collector as $exception => list($class, $method)) {
                $priority = $handler[2] ?? 0;
                $chain->addHandler([$class, $method], $priority);
            }
            ConsoleUtil::log(\sprintf('Error handler registered.'), [], 'debug', [
                'WorkerId' => $workerId
            ]);
        }
    }
}