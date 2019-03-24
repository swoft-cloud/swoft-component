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
     * @return bool
     */
    public function isTransaction(): bool
    {
        $key = sprintf('%d.transaction.%d.connection', Co::tid(), Co::id());
        return $this->has($key);
    }

    /**
     * Inc transactions for transaction
     */
    public function incTransactionTransactons(): void
    {
        $key = sprintf('%d.transaction.%d.transactions', Co::tid(), Co::id());

        $transactions = $this->get($key, 0);
        $this->set($key, $transactions + 1);
    }

    /**
     * Dec transactions for transaction
     */
    public function decTransactionTransactons(): void
    {
        $key = sprintf('%d.transaction.%d.transactions', Co::tid(), Co::id());

        $transactions = $this->get($key, 0);
        if ($transactions <= 0) {
            return;
        }

        $this->set($key, $transactions - 1);
    }

    /**
     * @param int $transactions
     */
    public function setTransactionTransactons(int $transactions): void
    {
        $key = sprintf('%d.transaction.%d.transactions', Co::tid(), Co::id());
        $this->set($key, $transactions);
    }

    /**
     * @return int
     */
    public function getTransactionTransactons(): int
    {
        $key = sprintf('%d.transaction.%d.transactions', Co::tid(), Co::id());
        return $this->get($key, 0);
    }

    /**
     * @param BaseConnection $connection
     */
    public function setTransactionConnection(BaseConnection $connection): void
    {
        $key = sprintf('%d.transaction.%d.connection', Co::tid(), Co::id());
        $this->set($key, $connection);
    }

    /**
     * @return Connection
     */
    public function getTransactionConnection(): Connection
    {
        $key = sprintf('%d.transaction.%d.connection', Co::tid(), Co::id());
        $con = $this->get($key, null);
        if (!$con instanceof Connection) {
            throw new \RuntimeException('Transaction is not beginning!');
        }

        return $con;
    }

    /**
     * Release transaction
     */
    public function releaseTransaction(): void
    {
        $key = sprintf('%d.transaction.%d', Co::tid(), Co::id());
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
                $ordConnection->forceRollBack(0);
                continue;
            }

            $ordConnection->release(true);
        }
    }
}