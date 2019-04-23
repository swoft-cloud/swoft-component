<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\BeanFactory;
use Swoft\Db\Connection\Connection;
use Swoft\Db\Connection\ConnectionManager;
use Swoft\Db\Exception\PoolException;
use Swoft\Db\Exception\QueryException;
use Swoft\Db\Query\Builder;
use Swoft\Db\Query\Expression;

/**
 * Class Db
 *
 * @see   Connection
 * @since 2.0
 *
 * @method static Builder table($table);
 * @method static Expression raw($value)
 * @method static mixed selectOne(string $query, $bindings = [], $useReadPdo = true)
 * @method static array select(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static \Generator cursor(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static bool insert(string $query, array $bindings = [])
 * @method static int update(string $query, array $bindings = [])
 * @method static int delete(string $query, array $bindings = [])
 * @method static bool statement(string $query, array $bindings = [])
 * @method static int affectingStatement(string $query, array $bindings = [])
 * @method static bool unprepared(string $query)
 * @method static mixed transaction(\Closure $callback, $attempts = 1)
 * @method static void beginTransaction()
 * @method static void commit()
 * @method static void rollBack(int $toLevel = null)
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
        'transaction',
        'beginTransaction',
        'commit',
        'rollBack',
    ];

    /**
     * @param string $name
     *
     * @return Connection
     * @throws PoolException
     */
    public static function connection(string $name = Pool::DEFAULT_POOL): Connection
    {
        try {
            $cm = bean(ConnectionManager::class);
            if ($cm->isTransaction()) {
                return $cm->getTransactionConnection();
            }

            return self::getConnectionFromPool($name);
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

        $connection = self::connection();
        return $connection->$name(...$arguments);
    }

    /**
     * Get connection from pool
     *
     * @param string $name
     *
     * @return Connection
     * @throws PoolException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Connection\Pool\Exception\ConnectionPoolException
     * @throws \Throwable
     */
    private static function getConnectionFromPool(string $name): Connection
    {
        $pool = \bean($name);
        if (!$pool instanceof Pool) {
            throw new PoolException(sprintf('%s is not instance of pool', $name));
        }

        /* @var ConnectionManager $conManager */
        $conManager = BeanFactory::getBean(ConnectionManager::class);
        $connection = $pool->getConnection();

        $connection->setRelease(true);
        $conManager->setOrdinaryConnection($connection);
        return $connection;
    }
}
