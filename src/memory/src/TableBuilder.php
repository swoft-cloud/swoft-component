<?php

namespace Swoft\Memory;

use Swoft\Memory\Exception\TableException;

/**
 * TableBuilder
 */
class TableBuilder
{
    /**
     * @var Table[]
     */
    private static $tables = [];

    /**
     * @param string $name
     * @param int    $size
     * @param array  $columns
     *
     * @return bool
     * @throws TableException
     */
    public static function create(string $name, int $size = 0, array $columns = []): bool
    {
        if (isset(self::$tables[$name])) {
            throw new TableException(sprintf('The %s table is exist!', $name));
        }

        $table = new Table($name, $size, $columns);
        $result = $table->create();
        self::$tables[$name] = $table;

        return $result;
    }

    /**
     * @param string $name
     *
     * @return Table
     * @throws TableException
     */
    public static function get(string $name): Table
    {
        if (!isset(self::$tables[$name])) {
            throw new TableException(sprintf('The %s table is not exist!', $name));
        }

        return self::$tables[$name];
    }
}