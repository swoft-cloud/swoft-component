<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Memory\Table;

use Swoole\Table;

/**
 * Table interface
 */
interface TableInterface
{
    /**
     * An int type of unit length
     */
    const ONE_INT_LENGTH = 1;

    /**
     * An int of two units of length
     */
    const TWO_INT_LENGTH = 2;

    /**
     * An int of four units of length
     */
    const FOUR_INT_LENGTH = 4;

    /**
     * An int of eight units of length
     */
    const EIGHT_INT_LENGTH = 8;

    /**
     * Int type
     */
    const TYPE_INT = Table::TYPE_INT;

    /**
     * String type
     */
    const TYPE_STRING = Table::TYPE_STRING;

    /**
     * Float type
     */
    const TYPE_FLOAT = Table::TYPE_FLOAT;

    /**
     * Add a column
     *
     * @param string $name Column name
     * @param int    $type Column type
     * @param int    $size Max length of column (in bits)
     * @return bool
     */
    public function column(string $name, int $type, int $size = 0): bool;

    /**
     * Create table by columns
     *
     * @return bool
     */
    public function create(): bool;

    /**
     * Set data
     *
     * @param string $key  Index key
     * @param array  $data Index data
     * @return bool
     */
    public function set(string $key, array $data): bool;

    /**
     * Get data by key and field
     *
     * @param string $key   Index key
     * @param string $field Filed name of Index
     * @return array|false Will return an array when success, return false when failure
     */
    public function get(string $key, $field = null);

    /**
     * Determine if column exist
     *
     * @param string $key Index key
     * @return bool
     */
    public function exist(string $key): bool;

    /**
     * Delete data by index key
     *
     * @param string $key Index key
     * @return bool
     */
    public function del(string $key): bool;

    /**
     * Increase
     *
     * @param string    $key    Index key
     * @param string    $field  Field of Index
     * @param int|float $incrBy Increase value, the value type should follow the original type of column
     * @return bool|int|float Will return false when failure, return the value after increased when success
     */
    public function incr(string $key, string $field, $incrBy = 1);

    /**
     * Decrease
     *
     * @param string    $key    Index key
     * @param string    $field  Field of Index
     * @param int|float $decrBy Decrease value, the value type should follow the original type of column
     * @return bool|int|float Will return false when failure, return the value after decreased when success
     */
    public function decr(string $key, string $field, $decrBy = 1);
}
