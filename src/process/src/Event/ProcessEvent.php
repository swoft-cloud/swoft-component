<?php

namespace Swoft\Process\Event;

/**
 * The process event
 */
class ProcessEvent
{
    /**
     * Before process
     */
    const BEFORE_PROCESS = "beforeProcess";

    /**
     * After process
     */
    const AFTER_PROCESS = "afterProcess";

    /**
     * Worker start
     */
    const WORKER_START = 'workerProcessStart';

    /**
     * Worker stop
     */
    const WORKER_STOP = 'workerProcessStop';

    /**
     * Message
     */
    const MESSAGE = 'processMessage';
}