<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Event;

use Swoole\Server;

/**
 * Class WorkerEvent
 *
 * @since 2.0
 */
class WorkerEvent extends ServerStartEvent
{
    /**
     * @var int
     */
    public $workerId = 0;

    /**
     * @var int
     */
    public $workerPid;

    /**
     * @var bool
     */
    public $taskProcess = false;

    /**
     * Class constructor.
     *
     * @param string $name
     * @param Server $server
     * @param int    $workerId
     */
    public function __construct(string $name, Server $server, int $workerId)
    {
        parent::__construct($name, $server);

        $this->workerId  = $workerId;
        $this->workerPid = $server->worker_pid;
    }
}
