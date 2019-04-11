<?php declare(strict_types=1);


namespace Swoft\Redis\Connection;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Stdlib\Collection;

/**
 * Class PhpRedisConnection
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class PhpRedisConnection extends Connection
{
    public function pipeline(callable $callback): array
    {
        return [];
    }

    public function transaction(callable $callback): array
    {
        return [];
    }
}