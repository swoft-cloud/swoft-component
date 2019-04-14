<?php declare(strict_types=1);


namespace Swoft\Redis\Connection;


class PhpRedisClusterConnection extends Connection
{
    public function pipeline(callable $callback): array
    {
        // TODO: Implement pipeline() method.
    }

    public function transaction(callable $callback): array
    {
        // TODO: Implement transaction() method.
    }

}