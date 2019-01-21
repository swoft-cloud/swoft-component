<?php

namespace Swoft\Http\Server\Event;

/**
 * Class HttpServerEvent
 *
 * @since 2.0
 */
class HttpServerEvent
{
    /**
     * Before onStart
     */
    const BEFORE_START = 'swoft.server.beforeStart';

    /**
     * After onStart
     */
    const AFTER_START = 'swoft.server.afterStart';

    /**
     * Before onWorkerStart
     */
    const BEFORE_WORKER_START = 'swoft.server.beforeWorkerStart';

    /**
     * After onWorkerStart
     */
    const AFTER_WORKER_START = 'swoft.server.afterWorkerStart';
}