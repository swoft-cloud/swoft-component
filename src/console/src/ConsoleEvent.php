<?php

namespace Swoft\Console;

/**
 * Class ConsoleEvent
 * @package Swoft\Console
 */
final class ConsoleEvent
{
    public const BEFORE_RUN      = 'console.run.before';
    public const BEFORE_DISPATCH = 'console.dispatch.before';
    public const BEFORE_EXECUTE  = 'console.execute.before';
    public const AFTER_EXECUTE   = 'console.execute.after';
    public const AFTER_DISPATCH  = 'console.dispatch.after';
    public const AFTER_RUN       = 'console.run.after';
}
