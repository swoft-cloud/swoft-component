<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Memory;

use Swoole\Table as SwooleTable;
use Swoft\Memory\Table\TableInterface;

/**
 * Memory Table
 *
 * @package Swoft\Memory
 */
class Table implements TableInterface
{
    /**
     * Swoole memory table instance
     *
     * @var SwooleTable $table
     */
    private $table;

    /**
     * Memory table name
     *
     * @var string $name
     */
    private $name = '';

    /**
     * Table size
     *
     * @var int $size
     */
    private $size = 0;

    /**
     * Table columns
     *
     * @var array $column
     * @example
     * [
     *     'field' => ['type', 'size']
     * ]
     */
    private $columns = [];

    /**
     * Is memory table created ?
     *
     * @var bool
     */
    private $create = false;

    /**
     * Table constructor.
     *
     * @param string $name
     * @param int $size
     * @param array $columns
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(string $name = '', int $size = 0, array $columns = [])
    {
        $this->setName($name);
        $this->setSize($size);
        $this->setColumns($columns);
    }

    /**
     * Set memory table instance
     *
     * @param SwooleTable $table Table instance
     * @return Table
     */
    public function setTable(SwooleTable $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Set memory table name
     *
     * @param string $name Memory table name
     * @return Table
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get memory table name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set memory table size
     *
     * @param int $size
     * @return Table
     */
    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the memory table instance
     *
     * @return SwooleTable
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function getTable(): SwooleTable
    {
        if (!$this->isCreate()) {
            throw new Exception\RuntimeException('Memory table have not been create');
        }
        return $this->table;
    }

    /**
     * Get memory table size that have been set
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set memory table columns structure
     *
     * @param array $columns
     * @return Table;
     * @throws Exception\InvalidArgumentException
     */
    public function setColumns(array $columns): self
    {
        foreach ($columns as $column => list($type, $size)) {
            list($type, $size) = $this->validateColumn($type, $size);
            $columns[$column] = [$type, $size];
        }
        $this->columns = $columns;
        return $this;
    }

    /**
     * Get memory table columns structure
     *
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add a column
     *
     * @param string $name Column name
     * @param int $type Column type
     * @param int $size Max length of column (in bits)
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    public function column(string $name, int $type, int $size = 0): bool
    {
        list($type, $size) = $this->validateColumn($type, $size);
        $this->columns[] = [$name, [$type, $size]];
        return true;
    }

    /**
     * Create table by columns
     *
     * @return bool
     * @throws Exception\RuntimeException When memory table have been created
     * @throws Exception\InvalidArgumentException
     */
    public function create(): bool
    {
        if ($this->isCreate()) {
            throw new Exception\RuntimeException('Memory table have been created, cannot recreated');
        }

        // Init memory table instance
        $table = new SwooleTable($this->getSize());
        $this->setTable($table);

        // Set columns
        foreach ($this->columns as $field => $fieldValue) {
            $args = array_merge([$field], $fieldValue);
            $this->table->column(...$args);
        }

        // Create memory table
        $result = $table->create();

        // Change memory table create status
        $this->setCreate(true);
        return $result;
    }

    /**
     * Set data
     *
     * @param string $key Index key
     * @param array $data Index data
     * @return bool
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function set(string $key, array $data): bool
    {
        if (!$this->isCreate()) {
            throw new Exception\RuntimeException('Memory table have not been create');
        }
        return $this->getTable()->set($key, $data);
    }

    /**
     * Get data by key and field
     *
     * @param string $key Index key
     * @param string $field Filed name of Index
     * @return array|false Will return an array when success, return false when failure
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function get(string $key, $field = null)
    {
        return null !== $field ? $this->getTable()->get($key, $field) : $this->getTable()->get($key);
    }

    /**
     * Determine if column exist
     *
     * @param string $key Index key
     * @return bool
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function exist(string $key): bool
    {
        return $this->getTable()->exist($key);
    }

    /**
     * Delete data by index key
     *
     * @param string $key Index key
     * @return bool
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function del(string $key): bool
    {
        return $this->getTable()->del($key);
    }

    /**
     * Increase
     *
     * @param string $key Index key
     * @param string $field Field of Index
     * @param int|float $incrBy Increase value, the value type should follow the original type of column
     * @return bool|int|float Will return false when failure, return the value after increased when success
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function incr(string $key, string $field, $incrBy = 1)
    {
        return $this->getTable()->incr($key, $field, $incrBy);
    }

    /**
     * Decrease
     *
     * @param string $key Index key
     * @param string $field Field of Index
     * @param int|float $decrBy Decrease value, the value type should follow the original type of column
     * @return bool|int|float|mixed Will return false when failure, return the value after decreased when success
     * @throws Exception\RuntimeException When memory table have not been create
     */
    public function decr(string $key, string $field, $decrBy = 1)
    {
        return $this->getTable()->decr($key, $field, $decrBy);
    }

    /**
     * @return bool
     */
    public function isCreate(): bool
    {
        return $this->create;
    }

    /**
     * @param bool $create
     * @return Table
     */
    public function setCreate($create): self
    {
        $this->create = $create;
        return $this;
    }

    /**
     * @param int $type
     * @param int $size
     * @return array
     * @throws Exception\InvalidArgumentException When size is unavailable
     */
    protected function validateColumn(int $type, int $size): array
    {
        switch ($type) {
            case self::TYPE_INT:
                if (!\in_array($size, [
                    self::ONE_INT_LENGTH,
                    self::TWO_INT_LENGTH,
                    self::FOUR_INT_LENGTH,
                    self::EIGHT_INT_LENGTH
                ], true)) {
                    $size = 4;
                }
                break;
            case self::TYPE_STRING:
                if ($size < 0) {
                    throw new Exception\InvalidArgumentException('Size unavailable, should greater than 0');
                }
                break;
            case self::TYPE_FLOAT:
                $size = 8;
                break;
            default:
                throw new Exception\InvalidArgumentException(sprintf('Undefined Column Type %s', $type));
        }
        return [$type, $size];
    }

    /**
     * Invoke
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function __call(string $method, array $args = [])
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$args);
        }
        throw new Exception\RuntimeException(printf('Call to undefined method %s', $method));
    }

    /**
     * __get
     *
     * @param string $name
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);

        if (!\method_exists($this, $method)) {
            throw new Exception\RuntimeException(sprintf('Call to undefined property %s', $name));
        }

        return $this->$method();
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (!\method_exists($this, $method)) {
            throw new Exception\RuntimeException(sprintf('Call to undefined property %s', $name));
        }

        return $this->$method();
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->$name);
    }
}
