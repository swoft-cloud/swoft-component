<?php declare(strict_types=1);


namespace Swoft\Connection\Pool;

use Swoft\Connection\Pool\Exception\ConnectionPoolException;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

/**
 * Class AbstractPool
 *
 * @since 2.0
 */
abstract class AbstractPool implements PoolInterface
{
    /**
     * Minimum active number of connections
     *
     * @var int
     */
    protected $minActive = 5;

    /**
     * Maximum active number of connections
     *
     * @var int
     */
    protected $maxActive = 10;

    /**
     * Maximum waiting for the number of connections, if there is no limit to 0
     *
     * @var int
     */
    protected $maxWait = 20;

    /**
     * Maximum waiting time(second)
     *
     * @var float
     */
    protected $maxWaitTime = 3;

    /**
     * Maximum idle time(second)
     *
     * @var int
     */
    protected $maxIdleTime = 60;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var \SplQueue
     */
    protected $queue;

    /**
     * Current count
     *
     * @var int
     */
    protected $count = 0;

    /**
     * @return ConnectionInterface
     * @throws ConnectionPoolException
     */
    public function getConnection(): ConnectionInterface
    {
        if (Coroutine::getCid() > 0) {
            $connection = $this->getConnectionByChannel();
        } else {
            $connection = $this->getConnectionByQueue();
        }

        if (!$connection->check()) {
            $connection->reconnect();
        }

        return $connection;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function release(ConnectionInterface $connection): void
    {
    }

    /**
     * Get connection by channel
     *
     * @return ConnectionInterface
     * @throws ConnectionPoolException
     */
    private function getConnectionByChannel(): ConnectionInterface
    {
        // Create channel
        if ($this->channel === null) {
            $this->channel = new Channel($this->maxActive);
        }

        // To reach `minActive` number
        if ($this->count < $this->minActive) {
            return $this->create();
        }

        // Pop connection
        $connection = null;
        if (!$this->channel->isEmpty()) {
            $connection = $this->popByChannel();
        }

        // Pop connection is not null
        if ($connection !== null) {
            return $connection;
        }

        // Channel is empty or  not reach `maxActive` number
        if ($this->count < $this->maxActive) {
            return $this->create();
        }

        // Out of `maxWait` number
        $stats = $this->channel->stats();
        if ($stats['consumer_num'] >= $this->maxWait) {
            throw new ConnectionPoolException(
                sprintf('Channel consumer is full, maxActive=%d, maxWait=%d, currentCount=%d',
                    $this->maxActive, $this->maxWaitTime, $this->count)
            );
        }

        // Sleep coroutine and resume coroutine after `maxWaitTime`, Return false is waiting timeout
        $connection = $this->channel->pop($this->maxWaitTime);
        if ($connection === false) {
            throw new ConnectionPoolException(
                sprintf('Channel pop timeout by %fs', $this->maxWaitTime)
            );
        }

        return $connection;
    }

    /**
     * @return ConnectionInterface
     * @throws ConnectionPoolException
     */
    private function getConnectionByQueue(): ConnectionInterface
    {
        // Create queue
        if ($this->queue == null) {
            $this->queue = new \SplQueue();
        }

        // To reach `minActive` number
        if ($this->count < $this->minActive) {
            return $this->create();
        }

        // Pop connection
        $connection = null;
        if (!$this->queue->isEmpty()) {
            $connection = $this->popByQueue();
        }

        // Pop connection is not null
        if ($connection !== null) {
            return $connection;
        }

        // Channel is empty or  not reach `maxActive` number
        if ($this->count < $this->maxWait) {
            return $this->create();
        }

        // Queue is full
        throw new ConnectionPoolException(
            sprintf('Queue is full, maxActive=%d, currentCount=%d', $this->maxActive, $this->count)
        );
    }

    /**
     * @return ConnectionInterface
     */
    private function create(): ConnectionInterface
    {
        $connection = $this->createConnection();
        $this->count++;

        return $connection;
    }

    /**
     * Pop by channel
     *
     * @return ConnectionInterface|null
     */
    private function popByChannel(): ?ConnectionInterface
    {
        $time       = time();
        $connection = null;

        while (!$this->channel->isEmpty()) {
            /* @var ConnectionInterface $connection */
            $connection = $this->channel->pop();
            $lastTime   = $connection->getLastTime();

            // Out of `maxIdleTime`
            if ($time - $lastTime > $this->maxIdleTime) {
                $this->count--;
                continue;
            }

            return $connection;
        }

        return $connection;
    }

    /**
     * Pop by queue
     *
     * @return ConnectionInterface|null
     */
    private function popByQueue(): ?ConnectionInterface
    {
        $time       = time();
        $connection = null;

        while (!$this->queue->isEmpty()) {
            /* @var ConnectionInterface $connection */
            $connection = $this->queue->pop();
            $lastTime   = $connection->getLastTime();

            // Out of `maxIdleTime`
            if ($time - $lastTime > $this->maxIdleTime) {
                $this->count--;
                continue;
            }

            return $connection;
        }

        return $connection;
    }
}