<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console;

/**
 * Class ConsoleEvent
 */
final class ConsoleEvent
{
    /**
     * On run before
     */
    public const RUN_BEFORE = 'console.run.before';

    /**
     * On show help before
     */
    public const SHOW_HELP_BEFORE = 'console.show.help.before';

    /**
     * On dispatch before
     */
    public const DISPATCH_BEFORE = 'console.dispatch.before';

    /**
     * On execute before
     */
    public const EXECUTE_BEFORE = 'console.execute.before';

    /**
     * On execute after
     */
    public const EXECUTE_AFTER = 'console.execute.after';

    /**
     * On dispatch after
     */
    public const DISPATCH_AFTER = 'console.dispatch.after';

    /**
     * On run after
     */
    public const RUN_AFTER = 'console.run.after';

    /**
     * On run error
     */
    public const RUN_ERROR = 'console.run.error';
}
