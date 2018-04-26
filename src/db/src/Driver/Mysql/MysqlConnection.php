<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Db\Driver\Mysql;

use Swoft\App;
use Swoft\Db\AbstractDbConnection;
use Swoft\Db\Bean\Annotation\Connection;
use Swoft\Db\Exception\MysqlException;
use Swoole\Coroutine\Mysql;
use Swoole\Coroutine\MySQL\Statement;

/**
 * Mysql connection
 *
 * @Connection()
 */
class MysqlConnection extends AbstractDbConnection
{
    /**
     * @var Mysql
     */
    private $connection;

    /**
     * @var string
     */
    private $sql = '';

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var Statement
     */
    private $stmt;

    /**
     * Prepare
     *
     * @param string $sql
     */
    public function prepare(string $sql)
    {
        $this->sql = $sql;
    }

    /**
     * Create connection
     *
     * @throws \InvalidArgumentException
     */
    public function createConnection()
    {
        $uri                = $this->pool->getConnectionAddress();
        $options            = $this->parseUri($uri);
        $options['timeout'] = $this->pool->getTimeout();

        // init
        $mysql = new MySQL();
        $mysql->connect([
            'host'     => $options['host'],
            'port'     => $options['port'],
            'user'     => $options['user'],
            'password' => $options['password'],
            'database' => $options['database'],
            'timeout'  => $options['timeout'],
            'charset'  => $options['charset'],
        ]);

        // error
        if ($mysql->connected === false) {
            throw new MysqlException('Database connection error，error=' . $mysql->connect_error);
        }

        $this->originDb   = $options['database'];
        $this->connection = $mysql;
    }

    /**
     * Execute
     *
     * @param array|null $params
     *
     * @return array|bool
     */
    public function execute(array $params = [])
    {
        list($sql, $params) = $this->parseSqlAndParams($this->sql, $params);
        $this->stmt = $this->connection->prepare($sql);
        $result     = $this->stmt->execute($params);
        if ($result === false) {
            throw new MysqlException('Mysql execute error，connectError=' . $this->connection->connect_error . ' error=' . $this->connection->error);
        }

        $this->pushSqlToStack($this->sql);

        return $result;
    }

    /**
     * @return mixed
     */
    public function receive()
    {
        $result = $this->connection->recv();
        if ($result === false) {
            throw new MysqlException('Mysql recv error，connectError=' . $this->connection->connect_error . ' error=' . $this->connection->error);
        }
        $this->connection->setDefer(false);

        $this->recv   = true;
        $this->result = $result;

        return $result;
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getInsertId()
    {
        return $this->connection->insert_id;
    }

    /**
     * @return int
     */
    public function getAffectedRows(): int
    {
        return $this->connection->affected_rows;
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        $this->connection->query('begin;');
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        if (!$this->recv) {
            $this->receive();
            App::error('You forget to getResult() before rollback !');
        }
        $this->connection->query('rollback;');
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        if (!$this->recv) {
            $this->receive();
            App::error('You forget to getResult() before commit !');
        }
        $this->connection->query('commit;');
    }

    /**
     * @param string $db
     */
    public function selectDb(string $db)
    {
        $this->connection->query(sprintf('use %s', $db));
        $this->currentDb = $db;
    }

    /**
     * @param bool $defer
     */
    public function setDefer($defer = true)
    {
        $this->recv = false;
        $result     = $this->connection->setDefer($defer);
    }

    /**
     * @return void
     */
    public function reconnect()
    {
        $this->createConnection();
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->connection->connected;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * Destroy sql
     */
    public function destroy()
    {
        $this->sql = '';
    }

    /**
     * @param string $sql
     * @param array  $params
     *
     * @return array
     * @throws \Swoft\Db\Exception\MysqlException
     */
    private function parseSqlAndParams(string $sql, array $params): array
    {
        $isIndexParam = strpos($sql, '?') !== false;
        $isKeyParam   = strpos($sql, ':') !== false;
        if ($isIndexParam && $isKeyParam) {
            throw new MysqlException('Placeholder can only be "?"/":" One of them');
        }
        if ($isIndexParam) {
            return [$sql, $params];
        }

        $sql    .= ' ';
        $result = preg_match_all('/(\:.*?)[\s+|\,|\)]/', $sql, $ary);
        if (!$result || !isset($ary[1])) {
            return [$sql, $params];
        }

        $newParams = [];
        foreach ($ary[1] as $name) {
            if (!array_key_exists($name, $params)) {
                throw new MysqlException($name . ' parameters must be passed');
            }
            $newParams[] = $params[$name];
        }

        $sql = str_replace($ary[1], '?', $sql);

        return [$sql, $newParams];
    }
}