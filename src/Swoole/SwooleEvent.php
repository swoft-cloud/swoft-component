<?php

namespace Swoft\Server\Swoole;

/**
 * Class SwooleEvent
 *
 * @since 2.0
 */
class SwooleEvent
{
    /**
     * Start
     */
    const START = 'start';

    /**
     * Shutdown
     */
    const SHUTDOWN = 'shutDown';

    /**
     * WorkerStart
     */
    const WORKER_START = 'workerStart';

    /**
     * WorkerStop
     */
    const WORKER_STOP = 'workerStop';

    /**
     * ManagerStart
     */
    const MANAGER_START = 'managerStart';

    /**
     * ManagerStop
     */
    const MANAGER_STOP = 'managerStop';

    /**
     * Task
     */
    const TASK = 'task';

    /**
     * Finish
     */
    const FINISH = 'finish';

    /**
     * PipeMessage
     */
    const PIPE_MESSAGE = 'pipeMessage';

    /**
     * WorkerError
     */
    const WORKER_ERROR = 'workerError';

    /**
     * Message
     */
    const MESSAGE = 'message';

    /**
     * Open
     */
    const OPEN = 'open';

    /**
     * Request
     */
    const REQUEST = 'request';

    /**
     * Packet
     */
    const PACKET = 'packet';

    /**
     * Receive
     */
    const RECEIVE = 'receive';

    /**
     * Connect
     */
    const CONNECT = 'connect';

    /**
     * Close
     */
    const CLOSE = 'close';

    /**
     * BufferFull
     */
    const BUFFER_FULL = 'bufferFull';

    /**
     * BufferEmpty
     */
    const BUFFER_EMPTY = 'bufferEmpty';

    /**
     * Event interface listener mapping
     */
    const LISTENER_MAPPING = [
        self::REQUEST => RequestInterface::class,
    ];
}