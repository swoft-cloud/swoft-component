<?php declare(strict_types=1);

namespace Swoft\ErrorHandler;

/**
 * Class ErrorType
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
    public const SYS  = 8;
    public const AUTO = 9;
}
