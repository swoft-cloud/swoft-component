<?php
 
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bootstrap\Listeners\Interfaces;

use Swoole\Server;

/**
 * Interface WorkerStartInterface
 * @package Swoft\Bootstrap\Listeners\Interfaces
 */
interface WorkerStartInterface
{
    public function onWorkerStart(Server $server, int $workerId, bool $isWorker);
}
