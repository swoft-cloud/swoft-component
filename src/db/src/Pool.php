<?php declare(strict_types=1);


namespace Swoft\Db;


use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Swoft\Db\Exception\DbException;

/**
 * Class Pool
 *
 * @since 2.0
 */
class Pool extends AbstractPool
{
    /**
     * Default pool name
     */
    const DEFAULT_POOL = 'db.pool';

    /**
     * Database
     *
     * @var Database
     */
    protected $database;

    /**
     * Create connection
     *
     * @return ConnectionInterface
     * @throws DbException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->database->createConnection($this);
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }
}
