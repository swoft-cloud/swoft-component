<?php declare(strict_types=1);

namespace Swoft\Log;

use Swoft\Co;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;

/**
 * Class Error
 *
 * @since 2.0
 */
class Error
{
    /**
     * @param string $message
     * @param mixed  ...$params
     */
    public static function log(string $message, ...$params): void
    {
        CLog::error($message, ...$params);

        // In coroutine to write application log
        if (Co::id() > 0) {
            Log::error($message, ...$params);
        }
    }
}
