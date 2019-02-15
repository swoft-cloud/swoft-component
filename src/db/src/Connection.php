<?php declare(strict_types=1);


namespace Swoft\Db;


use Swoft\Connection\Pool\ConnectionInterface as PoolConnectionInterface;
use Swoft\Db\Query\Builder;
use Swoft\Db\Query\Expression;
use Swoft\Db\Query\Grammar\Grammar;
use Swoft\Db\Query\Processor\Processor;

/**
 * Class Connection
 *
 * @since 2.0
 */
class Connection implements PoolConnectionInterface, ConnectionInterface
{
    /**
     * The active PDO connection.
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * The active PDO connection used for reads.
     *
     * @var \PDO
     */
    protected $readPdo;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Pool
     */
    protected $pool;

    /**
     * The query grammar implementation.
     *
     * @var Grammar
     */
    protected $queryGrammar;

    /**
     * @var Processor
     */
    protected $postProcessor;

    /**
     * @param Pool     $pool
     * @param Database $database
     */
    public function initialize(Pool $pool, Database $database)
    {
        $this->pool     = $pool;
        $this->database = $database;
    }

    /**
     * Get the query post processor used by the connection.
     *
     * @return Processor
     */
    public function getPostProcessor()
    {
        return $this->postProcessor;
    }

    /**
     * Set the query post processor used by the connection.
     *
     * @param  Processor $processor
     *
     * @return $this
     */
    public function setPostProcessor(Processor $processor)
    {
        $this->postProcessor = $processor;

        return $this;
    }

    /**
     * Create connection
     */
    public function create(): void
    {
        $this->createPdo();
        $this->createReadPdo();
    }

    public function reconnect(): void
    {
        // TODO: Implement reconnect() method.
    }

    public function check(): bool
    {
        return true;
    }

    public function getId(): string
    {
        return uniqid();
    }

    public function release(): void
    {
        // TODO: Implement release() method.
    }

    public function getLastTime(): int
    {
        return time();
    }

    /**
     * @return Grammar
     */
    public function getQueryGrammar(): Grammar
    {
        return $this->queryGrammar;
    }

    /**
     * @param Grammar $queryGrammar
     */
    public function setQueryGrammar(Grammar $queryGrammar): void
    {
        $this->queryGrammar = $queryGrammar;
    }

    private function createPdo()
    {
        $writes = $this->database->getWrites();
        $write  = $writes[0];

        $this->pdo = $this->database->getConnector()->connect($write);
    }

    private function createReadPdo()
    {
        $reads = $this->database->getReads();
        if (!empty($reads)) {
            $read          = $reads[0];
            $this->readPdo = $this->database->getConnector()->connect($read);
        }
    }

    public function table($table)
    {
        // TODO: Implement table() method.
    }

    public function raw($value)
    {
        // TODO: Implement raw() method.
    }

    public function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement selectOne() method.
    }

    public function select($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement select() method.
    }

    public function cursor($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement cursor() method.
    }

    public function insert($query, $bindings = [])
    {
        // TODO: Implement insert() method.
    }

    public function update($query, $bindings = [])
    {
        // TODO: Implement update() method.
    }

    public function delete($query, $bindings = [])
    {
        // TODO: Implement delete() method.
    }

    public function statement($query, $bindings = [])
    {
        // TODO: Implement statement() method.
    }

    public function affectingStatement($query, $bindings = [])
    {
        // TODO: Implement affectingStatement() method.
    }

    public function unprepared($query)
    {
        // TODO: Implement unprepared() method.
    }

    public function prepareBindings(array $bindings)
    {
        // TODO: Implement prepareBindings() method.
    }

    public function transaction(\Closure $callback, $attempts = 1)
    {
        // TODO: Implement transaction() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollBack()
    {
        // TODO: Implement rollBack() method.
    }

    public function transactionLevel()
    {
        // TODO: Implement transactionLevel() method.
    }

    public function pretend(\Closure $callback)
    {
        // TODO: Implement pretend() method.
    }


}