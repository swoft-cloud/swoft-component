<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Testing\Connection;

use Swoft\Pool\AbstractConnection;

class DemoConnection extends AbstractConnection
{
    /**
     * @var Client
     */
    protected $connection;

    public function createConnection()
    {
        $this->connection = new \stdClass();
        $this->connection->id = uniqid();
    }

    public function reconnect()
    {
        $this->createConnection();
    }

    public function check(): bool
    {
        return true;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
