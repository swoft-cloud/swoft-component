<?php declare(strict_types=1);

namespace Swoft\Console;

/**
 * Class ConsoleEvent
 * @package Swoft\Console
 */
final class ConsoleEvent
{
    public const BEFORE_RUN      = 'console.run.before';
    public const DISPATCH_BEFORE = 'console.dispatch.before';
    public const BEFORE_EXECUTE  = 'console.execute.before';
    public const AFTER_EXECUTE   = 'console.execute.after';
    public const DISPATCH_AFTER  = 'console.dispatch.after';
    public const AFTER_RUN       = 'console.run.after';
    public const ERROR_RUN       = 'console.run.error';
}
