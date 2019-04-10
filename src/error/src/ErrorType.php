<?php declare(strict_types=1);

namespace Swoft\Error;

/**
 * Class ErrorType
 * @since 2.0
 */
final class ErrorType
{
    public const WS   = 1;
    public const CLI  = 2;
    public const RPC  = 3;
    public const UDP  = 4;
    public const TCP  = 5;
    public const HTTP = 6;
    public const SOCK = 7;
    public const TASK = 8;
    public const SYS  = 15;

    // default
    public const DEF     = 20;
    public const DEFAULT = 20;
}
