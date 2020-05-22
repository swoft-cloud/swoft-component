<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Session;

use Swoft\Contract\SessionStorageInterface;
use Swoole\Table;

/**
 * Class SwooleStorage
 *
 * @since 2.0.8
 */
class SwooleStorage implements SessionStorageInterface
{
    public const KEY_FIELD = 'key';

    public const VAL_FIELD = 'val';

    /**
     * @var Table
     */
    private $db;

    /**
     * @var int
     */
    private $tableSize = 20480;

    /**
     * @var int
     */
    private $valueSize = 3072;

    /**
     * Init bean
     */
    public function init(): void
    {
        $this->create();
    }

    /**
     * destroy swoole table on exist
     */
    public function __destruct()
    {
        $this->db->destroy();
    }

    /**
     * create table
     */
    public function create(): void
    {
        $this->db = new Table($this->tableSize);

        // add columns
        $this->db->column(self::KEY_FIELD, Table::TYPE_STRING, 48);
        $this->db->column(self::VAL_FIELD, Table::TYPE_STRING, $this->valueSize);

        // Create it
        $this->db->create();
    }

    /**
     * Read session data
     *
     * @param string $storageKey The session id to read data for.
     *
     * @return string
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     */
    public function read(string $storageKey): string
    {
        if ($data = $this->db->get($storageKey, self::VAL_FIELD)) {
            return $data;
        }

        return '';
    }

    /**
     * Write session data
     *
     * @param string $storageKey  The session id.
     * @param string $sessionData The encoded session data. This data is a serialized
     *                            string and passing it as this parameter.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function write(string $storageKey, string $sessionData): bool
    {
        return $this->db->set($storageKey, [
            self::KEY_FIELD => $storageKey,
            self::VAL_FIELD => $sessionData,
        ]);
    }

    /**
     * Destroy a session
     *
     * @param string $storageKey The session ID being destroyed.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function destroy(string $storageKey): bool
    {
        return $this->db->del($storageKey);
    }

    /**
     * Whether the session exists
     *
     * @param string $storageKey
     *
     * @return bool
     */
    public function exists(string $storageKey): bool
    {
        return $this->db->exist($storageKey);
    }

    /**
     * clear table data
     */
    public function clear(): bool
    {
        foreach ($this->db as $row) {
            $this->db->del($row[self::KEY_FIELD]);
        }

        return true;
    }

    /**
     * @return Table
     */
    public function getDb(): Table
    {
        return $this->db;
    }

    /**
     * @return int
     */
    public function getTableSize(): int
    {
        return $this->tableSize;
    }

    /**
     * @param int $tableSize
     */
    public function setTableSize(int $tableSize): void
    {
        $this->tableSize = $tableSize;
    }

    /**
     * @return int
     */
    public function getValueSize(): int
    {
        return $this->valueSize;
    }

    /**
     * @param int $valueSize
     */
    public function setValueSize(int $valueSize): void
    {
        $this->valueSize = $valueSize;
    }
}
