<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Http\Server\Bootstrap\Listener;

use Swoft\App;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bootstrap\Listeners\Interfaces\WorkerStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Console\Helper\ConsoleUtil;
use Swoole\Server;

/**
 * Class WorkerStartListener
 *
 * @package Swoft\Http\Server\Bootstrap\Listener
 * @ServerListener(SwooleEvent::ON_WORKER_START)
 */
class WorkerStartListener implements WorkerStartInterface
{
    public function onWorkerStart(Server $server, int $workerId, bool $isWorker)
    {
        $isWorker && ConsoleUtil::log(
            \sprintf('Bean scan completed.'),
            [],
            'info',
            [
                'WorkerId' => App::getWorkerId()
            ]
        );
    }
}
