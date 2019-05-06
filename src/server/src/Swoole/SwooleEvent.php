<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

/**
 * Class SwooleEvent
 *
 * @since 2.0
 */
final class SwooleEvent
{
    /**
     * Start
     */
    public const START = 'start';

    /**
     * Shutdown
     */
    public const SHUTDOWN = 'shutdown';

    /**
     * WorkerStart
     */
    public const WORKER_START = 'workerStart';

    /**
     * WorkerStop
     */
    public const WORKER_STOP = 'workerStop';

    /**
     * WorkerError
     */
    public const WORKER_ERROR = 'workerError';

    /**
     * ManagerStart
     */
    public const MANAGER_START = 'managerStart';

    /**
     * ManagerStop
     */
    public const MANAGER_STOP = 'managerStop';

    /**
     * Task
     */
    public const TASK = 'task';

    /**
     * Finish
     */
    public const FINISH = 'finish';

    /**
     * PipeMessage
     */
    public const PIPE_MESSAGE = 'pipeMessage';

    /**
     * Handshake
     */
    public const HANDSHAKE = 'handshake';

    /**
     * Message
     */
    public const MESSAGE = 'message';

    /**
     * Open
     */
    public const OPEN = 'open';

    /**
     * Request
     */
    public const REQUEST = 'request';

    /**
     * Packet
     */
    public const PACKET = 'packet';

    /**
     * Receive
     */
    public const RECEIVE = 'receive';

    /**
     * Connect
     */
    public const CONNECT = 'connect';

    /**
     * Close
     */
    public const CLOSE = 'close';

    /**
     * BufferFull
     */
    public const BUFFER_FULL = 'bufferFull';

    /**
     * BufferEmpty
     */
    public const BUFFER_EMPTY = 'bufferEmpty';

    /**
     * Event interface listener mapping
     */
    public const LISTENER_MAPPING = [
        // For http server
        self::REQUEST   => RequestInterface::class,
        // For websocket server
        self::HANDSHAKE => HandshakeInterface::class,
        self::MESSAGE   => MessageInterface::class,
        // For tcp
        self::CLOSE     => CloseInterface::class,
        self::RECEIVE   => ReceiveInterface::class,
        self::CONNECT   => ConnectInterface::class,
        // For task
        self::TASK      => TaskInterface::class,
        self::FINISH    => FinishInterface::class,
    ];

    /**
     * for websocket
     */
    public const WS_EVENTS = [

    ];
}
