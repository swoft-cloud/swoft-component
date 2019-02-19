<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Exception\PoolException;

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
     * @throws PoolException
     */
    public static function pool(string $name = Pool::DEFAULT_POOL): Connection
    {
        try {
            $pool = bean($name);
            if (!$pool instanceof Pool) {
                throw new PoolException(sprintf('%s is not instance of pool', $name));
            }

            return $pool->getConnection();
        } catch (\Throwable $e) {
            throw new PoolException(sprintf('Pool error is %s', $e->getMessage()));
        }
    }
}