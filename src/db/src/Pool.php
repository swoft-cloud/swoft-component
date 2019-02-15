<?php declare(strict_types=1);


namespace Swoft\Db;


use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\ConnectionInterface;

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

    public function createConnection(): ConnectionInterface
    {
        return $this->database->createConnection($this);
    }
}