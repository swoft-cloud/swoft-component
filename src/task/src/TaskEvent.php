<?php declare(strict_types=1);


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
     * Finish event
     */
    public const FINISH = 'swoft.task.finish';

    /**
     * Before task
     */
    public const BEFORE_FINISH = 'swoft.task.finish.after';

    /**
     * After finish
     */
    public const AFTER_FINISH = 'swoft.task.finish.after';
}