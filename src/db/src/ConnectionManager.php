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


    /**
     * @param BaseConnection $connection
     */
    public function setOrdinaryConnection(BaseConnection $connection): void
    {
        $key = sprintf('%d.connection.%d', Co::tid(), $connection->getId());
        $this->set($key, $connection);
    }

    /**
     * @param int $id
     */
    public function releaseOrdinaryConnection(int $id)
    {
        $key = sprintf('%d.connection.%d', Co::tid(), $id);
        $this->unset($key);
    }

    /**
     * release
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function release(): void
    {
        $ordKey = sprintf('%d.connection', Co::tid());
        $tsKey  = sprintf('%d.transaction', Co::tid());

        $OrdConnections = $this->get($ordKey, []);
        $transactions   = $this->get($tsKey, []);

        $tsConnections = [];
        foreach ($transactions as $transaction) {
            $tsConnection = $transaction['connection'] ?? null;
            if ($tsConnection instanceof Connection) {
                $tsConnections[$tsConnection->getId()] = 1;
            }
        }

        foreach ($OrdConnections as $ordConnection) {
            if (!$ordConnection instanceof Connection) {
                continue;
            }

            $ordConId = $ordConnection->getId();
            if (isset($tsConnections[$ordConId])) {
                $ordConnection->rollBack();
            }
            $ordConnection->release(true);
        }
    }
}