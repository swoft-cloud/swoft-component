<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Core;

use Swoft\Pool\ConnectionInterface;

/**
 * Sync result
 */
abstract class AbstractDataResult implements ResultInterface
{
    /**
     * @var mixed
     */
    protected $connection;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * AbstractDataResult constructor.
     *
     * @param mixed $data
     * @param mixed $connection
     */
    public function __construct($data, $connection = null)
    {
        $this->data = $data;
        $this->connection = $connection;
    }

    /**
     * @return void
     */
    protected function release()
    {
        if ($this->connection instanceof ConnectionInterface) {
            $this->connection->release();
        }

        return;
    }
}
