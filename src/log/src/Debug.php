<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Log;

use Swoft\Co;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;

/**
 * Class Debug
 *
 * @since 2.0
 */
class Debug
{
    /**
     * @param string $message
     * @param mixed  ...$params
     */
    public static function log(string $message, ...$params): void
    {
        // Console log
        CLog::debug($message, ...$params);

        // In coroutine to write application log
        if (Co::id() > 0) {
            Log::debug($message, ...$params);
        }
    }
}
