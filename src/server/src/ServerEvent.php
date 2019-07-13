<?php declare(strict_types=1);

namespace Swoft\Server;

/**
 * Class ServerEvent
 *
 * @since 2.0
 */
final class ServerEvent
{
    /**
     * Before set swoole settings
     */
    public const BEFORE_SETTING = 'swoft.server.before.setting';

    /**
     * Before bind swoole events
     */
    public const BEFORE_BIND_EVENT = 'swoft.server.bind.event';

    /**
     * Before bind listener(s)
     */
    public const BEFORE_BIND_LISTENER = 'swoft.server.bind.listener.before';

    /**
     * After each listener is successfully added
     */
    public const AFTER_ADDED_LISTENER = 'swoft.server.added.listener.after';

    /**
     * Before bind process(es)
     */
    public const BEFORE_BIND_PROCESS = 'swoft.server.bind.process.before';

    /**
     * After each process is successfully added
     */
    public const AFTER_ADDED_PROCESS = 'swoft.server.added.process.after';

    /**
     * Swoft before start server event
     */
    public const BEFORE_START = 'swoft.server.start.before';

    /**
     * On task process start event
     */
    public const TASK_PROCESS_START = 'swoft.process.task.start';

    /**
     * On work process start event
     */
    public const WORK_PROCESS_START = 'swoft.process.work.start';

    /**
     * on user process start event
     */
    public const USER_PROCESS_START = 'swoft.process.user.start';

    /**
     * Before after event
     */
    public const AFTER_EVENT = 'swoft.server.event.after';

    /**
     * Before shutdown event
     */
    public const BEFORE_SHUTDOWN_EVENT = 'swoft.server.event.shutdown.before';

    /**
     * Before start event
     */
    public const BEFORE_START_EVENT = 'swoft.server.event.start.before';

    /**
     * Before worker error event
     */
    public const BEFORE_WORKER_ERROR_EVENT = 'swoft.server.event.worker.error.before';

    /**
     * Before worker start event
     */
    public const BEFORE_WORKER_START_EVENT = 'swoft.server.event.worker.start.before';

    /**
     * Before worker stop event
     */
    public const BEFORE_WORKER_STOP_EVENT = 'swoft.server.event.worker.stop.before';
}
