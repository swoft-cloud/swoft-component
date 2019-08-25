<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Storage;

use Swoft\WebSocket\Server\Contract\StorageInterface;
use Swoole\Table;

/**
 * Class SwooleStorage
 *
 * @since 2.0.6
 */
class SwooleStorage implements StorageInterface
{
    /**
     * @var Table
     */
    private $db;

    /**
     * @var int
     */
    private $size = 20480;

    // public function __construct()
    // {
    //     $this->create();
    // }

    public function create(): void
    {
        $this->db = new Table($this->size);
        $this->db->column('key', Table::TYPE_STRING, 72);
        $this->db->column('data', Table::TYPE_STRING, 3072);
        $this->db->create();
    }

    /**
     * @return Table
     */
    public function getDb(): Table
    {
        return $this->db;
    }

    /**
     * @param Table $db
     */
    public function setDb(Table $db): void
    {
        $this->db = $db;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void
    {
        $this->db->set($key, [
            'key'  => $key,
            'data' => $value,
        ]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->db->get($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function del(string $key): bool
    {
        return $this->db->del($key);
    }

    /**
     * clear table data
     */
    public function clear(): void
    {
        foreach ($this->db as $row) {
            $this->db->del($row['key']);
        }
    }
}
