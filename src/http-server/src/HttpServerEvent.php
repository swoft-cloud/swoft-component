<?php

namespace Swoft\Http\Server;

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
    public const BEFORE_START = 'swoft.server.beforeStart';

    /**
     * After onStart
     */
    public const AFTER_START = 'swoft.server.afterStart';

    /**
     * Before onWorkerStart
     */
    public const BEFORE_WORKER_START = 'swoft.server.beforeWorkerStart';

    /**
     * After onWorkerStart
     */
    public const AFTER_WORKER_START = 'swoft.server.afterWorkerStart';
}