<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process;

use Swoft\Process\Contract\WorkerStartInterface;
use Swoft\Process\Contract\WorkerStopInterface;
use Swoft\Process\Contract\MessageInterface;

/**
 * Class SwooleEvent
 *
 * @since 2.0
 */
class SwooleEvent
{
    /**
     * Worker start
     */
    public const WORKER_START = 'workerStart';

    /**
     * Worker stop
     */
    public const WORKER_STOP = 'workerStop';

    /**
     * On message
     */
    public const MESSAGE = 'message';

    /**
     * Listener mapping
     */
    public const LISTENER_MAPPING = [
        self::WORKER_START => WorkerStartInterface::class,
        self::WORKER_STOP  => WorkerStopInterface::class,
        self::MESSAGE      => MessageInterface::class
    ];
}
