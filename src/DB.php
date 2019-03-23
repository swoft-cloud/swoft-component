<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\BeanFactory;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Exception\QueryException;
use Swoft\Db\Query\Builder;

/**
 * Class Db
 *
 * @see   Connection
 * @since 2.0
 *
 * @method static Builder table($table);
 */
class DB
{
    /**
     * Supported methods
     *
     * @var array
     */
    private static $passthru = [
        'table',
        'raw',
        'selectOne',
        'select',
        'cursor',
        'insert',
        'update',
        'delete',
        'statement',
        'affectingStatement',
        'unprepared',
        'prepareBindings',
        'transaction',
        'beginTransaction',
        'commit',
        'rollBack',
        'transactionLevel',
        'pretend',
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

            /* @var ConnectionManager $conManager*/
            $conManager = BeanFactory::getBean(ConnectionManager::class);
            $connection = $pool->getConnection();

            $connection->setRelease(true);
            $conManager->setOrdinaryConnection($connection);
            return $connection;
        } catch (\Throwable $e) {
            throw new PoolException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }
    }

    /**
     * Call method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @throws PoolException
     * @throws QueryException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (!in_array($name, self::$passthru)) {
            throw new QueryException(sprintf('Method(%s) is not exist!', $name));
        }

        $connection = self::pool();
        return $connection->$name(...$arguments);
    }
}