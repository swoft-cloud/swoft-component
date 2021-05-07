<?php declare(strict_types=1);

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
