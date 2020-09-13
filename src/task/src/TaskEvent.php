<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task;

/**
 * Class TaskEvent
 *
 * @since 2.0
 */
class TaskEvent
{
    /**
     * Before task
     */
    public const BEFORE_TASK = 'swoft.task.task.before';

    /**
     * After task
     */
    public const AFTER_TASK = 'swoft.task.task.after';

    /**
     * Before task
     */
    public const BEFORE_FINISH = 'swoft.task.finish.before';

    /**
     * Finish event
     */
    public const FINISH = 'swoft.task.finish';

    /**
     * After finish
     */
    public const AFTER_FINISH = 'swoft.task.finish.after';
}
