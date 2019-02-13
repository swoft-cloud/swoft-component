<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Db\Connector\ConnectorInterface;
use Swoft\Db\Connector\MySqlConnector;
use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class Database
 *
 * @since 2.0
 */
class Database
{
    /**
     * Mysql driver name
     */
    const MYSQL = 'mysql';

    /**
     * The Data Source Name, or DSN, contains the information required to connect to the database.
     * Please refer to the [PHP manual](http://php.net/manual/en/pdo.construct.php) on
     * the format of the DSN string.
     *
     * For [SQLite](http://php.net/manual/en/ref.pdo-sqlite.connection.php) you may use a [path alias](guide:concept-aliases)
     * for specifying the database path, e.g. `sqlite:@app/data/db.sql`.
     *
     * @var string
     */
    protected $dsn = '';

    /**
     * The username for establishing DB connection. Defaults to `null` meaning no username to use.
     *
     * @var string
     */
    protected $username = '';

    /**
     * The password for establishing DB connection. Defaults to `null` meaning no password to use.
     *
     * @var string
     */
    protected $password = '';

    /**
     * The charset used for database connection. The property is only used
     * for MySQL, PostgreSQL and CUBRID databases. Defaults to null, meaning using default charset
     * as configured by the database.
     *
     * @var string
     */
    protected $charset = '';

    /**
     * The common prefix or suffix for table names.
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * PDO options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Other config
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $connectors = [];

    /**
     * @return Connection
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function createConnection(): Connection
    {
        $driver     = $this->getDriver();
        $connectors = ArrayHelper::merge($this->defaultConnectors(), $this->connectors);
        $connector  = $connectors[$driver] ?? null;

        if (!$connector instanceof ConnectorInterface) {

        }

        return $connector->connect($this);
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        if (($pos = strpos($this->dsn, ':')) !== false) {
            return $this->_driverName = strtolower(substr($this->dsn, 0, $pos));
        } else {
            $this->_driverName = strtolower($this->getSlavePdo()->getAttribute(PDO::ATTR_DRIVER_NAME));
        }
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function defaultConnectors()
    {
        return [
            self::MYSQL => bean(MySqlConnector::class)
        ];
    }

    /**
     * @return string
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}