<?php declare(strict_types=1);


namespace Swoft\Db;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Concern\DataPropertyTrait;
use Swoft\Connection\Pool\ConnectionInterface as BaseConnection;


/**
 * Class ConnectionManager
 *
 * @since 2.0
 *
 * @Bean()
 */
class ConnectionManager
{
    /**
     * @example
     * [
     *  'tid' => [
     *      'transaction' => [
     *          'cid' => [
     *              'transactions' => 0,
     *              'connection' => Connection
     *          ]
     *      ],
     *
     *     'connection' => [
     *          'connectionId' => Connection
     *      ]
     *   ],
     *  'tid2' => [
     *      'transaction' => [
     *          'cid' => [
     *              'transactions' => 0,
     *              'connection' => Connection
     *          ]
     *      ],
     *
     *     'connection' => [
     *          'connectionId' => Connection
     *      ]
     *   ],
     * ]
     */
    use DataPropertyTrait;

    public function setOrdinaryConnection(BaseConnection $connection): void
    {
        $key = sprintf('%d.connection.%d', Co::tid(), $connection->getId());
        $this->set($key, $connection);
    }
}