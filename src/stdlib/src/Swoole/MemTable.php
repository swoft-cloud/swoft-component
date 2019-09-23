<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Stdlib\Swoole;

use InvalidArgumentException;
use RuntimeException;
use Swoole\Coroutine;
use Swoole\Table;
use function count;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function json_encode;

/**
 * Class MemTable - an simple memory table base on swoole table
 */
class MemTable
{
    /**
     * Swoole memory table instance
     *
     * @var Table $table
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
     * [
     *     'field' => ['type', 'size']
     * ]
     *
     * @var array $column
     */
    private $columns = [];

    /**
     * Is memory table created ?
     *
     * @var bool
     */
    private $create = false;

    /**
     * @var string
     */
    private $dbFile = '';

    /**
     * Table constructor.
     *
     * @param string $name
     * @param int    $size
     * @param array  $columns ['field' => ['type', 'size']]
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $name = '', int $size = 0, array $columns = [])
    {
        $this->setName($name);
        $this->setSize($size);
        $this->setColumns($columns);
    }

    /**
     * Set memory table columns
     *
     * @param array $columns
     *
     * @throws InvalidArgumentException
     */
    public function setColumns(array $columns): void
    {
        foreach ($columns as $column => [$type, $size]) {
            $this->columns[$column] = [(int)$type, (int)$size];
        }
    }

    /**
     * Add a column
     *
     * @param string $name Column name
     * @param int    $type Column type. {@see Table::TYPE_INT, Table::TYPE_FLOAT, Table::TYPE_STRING}
     * @param int    $size Max length of column (in bits)
     *
     * @return self
     */
    public function addColumn(string $name, int $type, int $size = 0): self
    {
        $this->columns[$name] = [$type, $size];

        return $this;
    }

    /**
     * Create table by columns
     *
     * @return bool
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function create(): bool
    {
        if ($this->isCreate()) {
            throw new RuntimeException('Memory table have been created, cannot recreated');
        }

        // Create memory table instance
        $table = new Table($this->getSize());
        $this->setTable($table);

        // Set columns
        foreach ($this->columns as $name => [$type, $size]) {
            $this->table->column($name, $type, $size);
        }

        // Create memory table
        $result = $table->create();

        // Change memory table create status
        $this->setCreate(true);
        return $result;
    }

    /*****************************************************************************
     * Operate data methods
     ****************************************************************************/

    /**
     * Set data by key
     *
     * @param string $key Index key
     * @param array  $data
     *
     * @return bool
     * @throws RuntimeException
     */
    public function set(string $key, array $data): bool
    {
        if (!$this->isCreate()) {
            throw new RuntimeException('Memory table have not been create');
        }

        return $this->getTable()->set($key, $data);
    }

    /**
     * Get data by key and field
     *
     * @param string $key   Index key
     * @param string $field Filed name of Index
     *
     * @return array|false Will return an array when success, return false when failure
     * @throws RuntimeException
     */
    public function get(string $key, string $field = null)
    {
        return $this->getTable()->get($key, $field);
    }

    /**
     * Determine if column exist
     *
     * @param string $key Index key
     *
     * @return bool
     * @throws RuntimeException
     */
    public function exist(string $key): bool
    {
        return $this->getTable()->exist($key);
    }

    /**
     * Delete data by index key
     *
     * @param string $key Index key
     *
     * @return bool
     * @throws RuntimeException
     */
    public function del(string $key): bool
    {
        return $this->getTable()->del($key);
    }

    /**
     * Increase
     *
     * @param string    $key    Index key
     * @param string    $field  Field of Index
     * @param int|float $incrby Increase value, the value type should follow the original type of column
     *
     * @return bool|int|float Will return false when failure, return the value after increased when success
     * @throws RuntimeException
     */
    public function incr(string $key, string $field, $incrby = 1)
    {
        return $this->getTable()->incr($key, $field, $incrby);
    }

    /**
     * Decrease
     *
     * @param string    $key    Index key
     * @param string    $field  Field of Index
     * @param int|float $decrBy Decrease value, the value type should follow the original type of column
     *
     * @return bool|int|float Will return false when failure, return the value after decreased when success
     * @throws RuntimeException
     */
    public function decr(string $key, string $field, $decrBy = 1)
    {
        return $this->getTable()->decr($key, $field, $decrBy);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->table);
    }

    /*****************************************************************************
     * Extra methods
     ****************************************************************************/

    /**
     * flush table
     */
    // public function flush(): void
    // {
    //     foreach ($this->table as $row) {
    //         $this->table->del($row['key']);
    //     }
    // }

    /**
     * Restore data from dbFile
     *
     * @param bool $coRead
     */
    public function restore(bool $coRead = false): void
    {
        $file = $this->dbFile;
        if (!$file || !file_exists($file)) {
            return;
        }

        if ($coRead) {
            $content = Coroutine::readFile($file);
        } else {
            $content = file_get_contents($file);
        }

        $this->load((array)json_decode($content, true));
    }

    /**
     * Export memory data to dbFile
     *
     * @param bool $coWrite
     */
    public function dump(bool $coWrite = false): void
    {
        if (!$file = $this->dbFile) {
            return;
        }

        $data = [];
        foreach ($this->table as $row) {
            $data[] = $row;
        }

        if ($coWrite) {
            Coroutine::writeFile($file, json_encode($data));
        } else {
            file_put_contents($file, json_encode($data));
        }
    }

    /**
     * @param array $data
     */
    public function load(array $data): void
    {
        foreach ($data as $row) {
            if (isset($row['text'])) {
                $this->table->set($row['text'], $row);
            }
        }
    }


    /*****************************************************************************
     * Getter/Setter methods
     ****************************************************************************/

    /**
     * Set memory table instance
     *
     * @param Table $table Table instance
     */
    public function setTable(Table $table): void
    {
        $this->table = $table;
    }

    /**
     * Get the memory table instance
     *
     * @return Table
     * @throws RuntimeException
     */
    public function getTable(): Table
    {
        if (!$this->isCreate()) {
            throw new RuntimeException('Memory table have not been create');
        }

        return $this->table;
    }

    /**
     * Set memory table name
     *
     * @param string $name Memory table name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * Get memory table size that have been set
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
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
     * @return string
     */
    public function getDbFile(): string
    {
        return $this->dbFile;
    }

    /**
     * @param string $dbFile
     */
    public function setDbFile(string $dbFile): void
    {
        $this->dbFile = $dbFile;
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
     */
    public function setCreate($create): void
    {
        $this->create = $create;
    }
}
