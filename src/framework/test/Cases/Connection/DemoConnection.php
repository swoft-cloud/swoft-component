<?php
namespace SwoftTest\Connection;

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