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

/**
 * Class ProcessEvent
 *
 * @since 2.0
 */
class ProcessEvent
{
    /**
     * Before user process
     */
    public const BEFORE_USER_PROCESS = 'swoft.process.user.before';

    /**
     * After user process
     */
    public const AFTER_USER_PROCESS = 'swoft.process.user.after';

    /**
     * Before process
     */
    public const BEFORE_PROCESS_START = 'swoft.process.start.before';

    public const AFTER_PROCESS_START  = 'swoft.process.start.after';

    /**
     * After process
     */
    public const BEFORE_PROCESS_STOP = 'swoft.process.stop.before';

    public const AFTER_PROCESS_STOP  = 'swoft.process.stop.after';
}
