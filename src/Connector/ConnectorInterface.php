<?php declare(strict_types=1);


namespace Swoft\Db\Connector;


use Swoft\Db\Database;

/**
 * Class ConnectorInterface
 *
 * @since 2.0
 */
interface ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  Database $db
     *
     * @return \PDO
     */
    public function connect(Database $db): \PDO;
}