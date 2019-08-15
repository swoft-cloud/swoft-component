<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Storage;

use Swoole\Table;

/**
 * Class SwooleStorage
 *
 * @since 2.0.6
 */
class SwooleStorage
{
    /**
     * @var Table
     */
    private $db;

    public function create(): void
    {
        // $db = new Table(102400);
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
}
