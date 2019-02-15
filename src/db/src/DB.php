<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Connection\Pool\PoolInterface;

/**
 * Class Db
 *
 * @see   Connection
 * @since 2.0
 */
class DB
{
    public static function pool(string $name = Pool::DEFAULT_POOL): Connection
    {
        $pool = bean($name);
        if (!$pool instanceof Pool) {

        }
        return $pool->getConnection();
    }
}