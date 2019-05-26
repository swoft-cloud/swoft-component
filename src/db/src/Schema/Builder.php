<?php declare(strict_types=1);


namespace Swoft\Db\Schema;

use Closure;
use LogicException;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Connection\Connection;
use Swoft\Db\DB;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Pool;
use Swoft\Db\Schema\Grammars\Grammar;
use function array_map;
use function call_user_func;
use function in_array;
use function is_callable;
use function strtolower;
use function tap;

/**
 * Class Schema Builder
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class Builder
{
    use PrototypeTrait;

    /**
     * Select db pool
     *
     * @var string
     */
    public $poolName = Pool::DEFAULT_POOL;

    /**
     * The database query grammar instance.
     *
     * @var Grammar
     */
    public $grammar;

    /**
     * The default string length for migrations.
     *
     * @var int
     */
    public $defaultStringLength = 255;

    /**
     * The Blueprint resolver callback.
     *
     * @var Closure
     */
    protected $resolver;

    /**
     * New schema builder
     *
     * @param string $poolName
     *
     * @return Builder
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new($poolName = Pool::DEFAULT_POOL): self
    {
        $instance = self::__instance();

        $instance->poolName = $poolName;

        return $instance;
    }

    /**
     * Determine if the given table exists.
     *
     * @param string $table
     *
     * @return bool
     * @throws DbException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function hasTable(string $table): bool
    {
        $table = $this->getTableName($table);

        return $this->getConnection()->statement($this->grammar->compileTableExists(), [$table]);
    }

    /**
     * Return complete table name
     *
     * @param string $table
     *
     * @return string
     */
    public function getTableName(string $table): string
    {
        return $this->grammar->getTablePrefix() . $table;
    }

    /**
     * Determine if the given table has a given column.
     *
     * @param string $table
     * @param string $column
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function hasColumn(string $table, string $column): bool
    {
        return in_array(
            strtolower($column), array_map('strtolower', $this->getColumnListing($table))
        );
    }

    /**
     * Determine if the given table has given columns.
     *
     * @param       $table
     * @param array $columns
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function hasColumns($table, array $columns)
    {
        $tableColumns = array_map('strtolower', $this->getColumnListing($table));

        foreach ($columns as $column) {
            if (!in_array(strtolower($column), $tableColumns)) {
                return false;
            }
        }

        return true;
    }

    /**
     *  Get the column listing for a given table.
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
        $table   = $this->getTableName($table);
        $results = $this->getConnection()->select(
            $this->grammar->compileColumnListing(), [$table], false
        );

        return $this->getConnection()->getPostProcessor()->processColumnListing($results);
    }

    /**
     * Modify a table on the schema.
     *
     * @param string  $table
     * @param Closure $callback
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function table(string $table, Closure $callback)
    {
        $this->build($this->createBlueprint($table, $callback));
    }

    /**
     * Create a new table on the schema.
     *
     * @param string  $table
     * @param Closure $callback
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function create(string $table, Closure $callback)
    {
        $this->build(tap($this->createBlueprint($table), function (Blueprint $blueprint) use ($callback) {
            $blueprint->create();

            $callback($blueprint);
        }));
    }

    /**
     * Drop a table from the schema.
     *
     * @param string $table
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function drop(string $table)
    {
        $this->build(tap($this->createBlueprint($table), function (Blueprint $blueprint) {
            $blueprint->drop();
        }));
    }

    /**
     * Drop a table from the schema if it exists.
     *
     * @param string $table
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function dropIfExists(string $table)
    {
        $this->build(tap($this->createBlueprint($table), function (Blueprint $blueprint) {
            $blueprint->dropIfExists();
        }));
    }

    /**
     *  Rename a table on the schema.
     *
     * @param string $from
     * @param string $to
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function rename(string $from, string $to)
    {
        $this->build(tap($this->createBlueprint($from), function (Blueprint $blueprint) use ($to) {
            $blueprint->rename($to);
        }));
    }

    /**
     * Enable foreign key constraints.
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function enableForeignKeyConstraints()
    {
        return $this->getConnection()->statement(
            $this->grammar->compileEnableForeignKeyConstraints()
        );
    }

    /**
     * Disable foreign key constraints.
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function disableForeignKeyConstraints()
    {
        return $this->getConnection()->statement(
            $this->grammar->compileDisableForeignKeyConstraints()
        );
    }

    /**
     * Execute the blueprint to build / modify the table.
     *
     * @param Blueprint $blueprint
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    protected function build(Blueprint $blueprint)
    {
        $blueprint->build($this->getConnection(), $this->grammar);
    }

    /**
     * Create a new command set with a Closure.
     *
     * @param string       $table
     * @param Closure|null $callback
     *
     * @return Blueprint
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function createBlueprint(string $table, Closure $callback = null)
    {
        $prefix = $this->grammar->getTablePrefix();

        if (is_callable($this->resolver)) {
            return call_user_func($this->resolver, $table, $callback, $prefix);
        }

        return Blueprint::new($table, $callback, $prefix);
    }

    /**
     * Set the Schema Blueprint resolver callback.
     *
     * @param \Closure $resolver
     *
     * @return void
     */
    public function blueprintResolver(Closure $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Get the database connection instance.
     *
     * @return Connection
     * @throws DbException
     */
    public function getConnection()
    {
        $connection = DB::connection($this->poolName);

        return $connection;
    }

    /**
     * Drop all tables from the database.
     *
     * @return void
     *
     * @throws LogicException
     */
    public function dropAllTables()
    {
        throw new LogicException('This database driver does not support dropping all tables.');
    }

    /**
     * Drop all views from the database.
     *
     * @return void
     *
     * @throws LogicException
     */
    public function dropAllViews()
    {
        throw new LogicException('This database driver does not support dropping all views.');
    }

    /**
     * Drop all types from the database.
     *
     * @return void
     *
     * @throws LogicException
     */
    public function dropAllTypes()
    {
        throw new LogicException('This database driver does not support dropping all types.');
    }
}
