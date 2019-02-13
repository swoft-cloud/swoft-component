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
    /**
     * @param string $name
     *
     * @return Connection
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function pool(string $name = Pool::DEFAULT_POOL): Connection
    {
        $pool = bean($name);
        if (!$pool instanceof PoolInterface) {

        }
        return $pool->getConnection();
    }
}