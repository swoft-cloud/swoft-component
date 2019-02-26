<?php

namespace Swoft\Server\Event;

use Co\Server;

/**
 * Class WorkerEvent
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

    public function __construct(string $name, Server $server, int $workerId)
    {
        parent::__construct($name, $server);

        $this->workerPid = $server->worker_pid;
    }
}
