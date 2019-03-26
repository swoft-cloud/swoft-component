<?php declare(strict_types=1);


namespace Swoft\Rpc\Client;


use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\ConnectionInterface;

/**
 * Class Pool
 *
 * @since 2.0
 */
class Pool extends AbstractPool
{
    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        return new Connection();
    }
}