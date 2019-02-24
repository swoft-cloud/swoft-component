<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Db\Exception\PoolException;
use Swoft\Db\Query\Builder;

/**
 * Class Db
 *
 * @see   Connection
 * @since 2.0
 */
class DB
{
    /**
     * @var array
     */
    private static $passthru = [
        'table',
        'insert',
    ];

    /**
     * @param string $name
     *
     * @return Connection
     * @throws PoolException
     */
    public static function pool(string $name = Pool::DEFAULT_POOL): Connection
    {
        try {
            $pool = \bean($name);
            if (!$pool instanceof Pool) {
                throw new PoolException(sprintf('%s is not instance of pool', $name));
            }

            return $pool->getConnection();
        } catch (\Throwable $e) {
            throw new PoolException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }
    }


    public static function __callStatic($name, $arguments)
    {
        if (!in_array($name, self::$passthru)) {

        }

        $connection = self::pool();

        return $connection->$name(...$arguments);

        // TODO: Implement __callStatic() method.
    }
}