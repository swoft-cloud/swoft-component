<?php declare(strict_types=1);


namespace Swoft\Db\Schema;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Exception\DbException;

/**
 * Class Builder
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class MySqlBuilder extends Builder
{

    /**
     * Determine if the given table exists.
     *
     * @param string $table
     *
     * @return bool
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function hasTable(string $table): bool
    {
        $table = $this->getTableName($table);

        return count($this->getConnection()->select(
                $this->grammar->compileTableExists(), [$this->getDatabaseName(), $table]
            )) > 0;
    }

    /**
     * Get the column listing for a given table.
     *
     * @param string $table
     *
     * @return array
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function getColumnListing(string $table)
    {
        $table      = $this->getTableName($table);
        $connection = $this->getConnection();
        $results    = $connection->select(
            $this->grammar->compileColumnListing(), [$this->getDatabaseName(), $table]
        );
        return $connection->getPostProcessor()->processColumnListing($results);
    }

    /**
     * Drop all tables from the database.
     *
     * @return void
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function dropAllTables()
    {
        $tables = [];

        foreach ($this->getAllTables() as $row) {
            $row = (array)$row;

            $tables[] = reset($row);
        }

        if (empty($tables)) {
            return;
        }

        $this->disableForeignKeyConstraints();

        $this->getConnection()->statement(
            $this->grammar->compileDropAllTables($tables)
        );

        $this->enableForeignKeyConstraints();
    }

    /**
     * Drop all views from the database.
     *
     * @return void
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function dropAllViews()
    {
        $views = [];

        foreach ($this->getAllViews() as $row) {
            $row = (array)$row;

            $views[] = reset($row);
        }

        if (empty($views)) {
            return;
        }

        $this->getConnection()->statement(
            $this->grammar->compileDropAllViews($views)
        );
    }

    /**
     * Get all of the table names for the database.
     *
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    protected function getAllTables()
    {
        return $this->getConnection()->select(
            $this->grammar->compileGetAllTables()
        );
    }

    /**
     * Get all of the view names for the database.
     *
     * @return array
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    protected function getAllViews()
    {
        return $this->getConnection()->select(
            $this->grammar->compileGetAllViews()
        );
    }
}
