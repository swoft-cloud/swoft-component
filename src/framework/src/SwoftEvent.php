<?php declare(strict_types=1);

namespace Swoft;

/**
 * Class SwoftEvent
 * @since 2.0
 */
final class SwoftEvent
{
    /**
     * Swoft init complete
     */
    public const APP_INIT_COMPLETE  = 'swoft.init.complete';

    /**
     * Session complete
     *  - webSocket connection close
     */
    public const SESSION_COMPLETE = 'swoft.session.complete';

    /**
     * Coroutine complete
     */
    public const COROUTINE_COMPLETE = 'swoft.co.complete';

    /**
     * Coroutine destroy
     */
    public const COROUTINE_DESTROY = 'swoft.co.destroy';

    /**
     * Coroutine defer
     */
    public const COROUTINE_DEFER = 'swoft.co.defer';

    /**
     * Worker shutdown
     */
    public const WORKER_SHUTDOWN = 'swoft.worker.shutdown';
}
