<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Redis;

/**
 * Class RedisEvent
 *
 * @since 2.0
 */
class RedisEvent
{
    /**
     * Before command
     */
    public const BEFORE_COMMAND = 'swoft.redis.command.before';

    /**
     * After command
     */
    public const AFTER_COMMAND = 'swoft.redis.command.after';
}
