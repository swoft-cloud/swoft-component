<?php declare(strict_types=1);


namespace Swoft\Connection\Pool;

/**
 * Class AbstractConnection
 *
 * @since 2.0
 */
abstract class AbstractConnection implements ConnectionInterface
{
    /**
     * @var PoolInterface
     */
    protected $pool;

    /**
     * Whether to release connection
     *
     * @var bool
     */
    protected $release = false;

    /**
     * @param bool $release
     */
    public function setRelease(bool $release): void
    {
        $this->release = $release;
    }

    /**
     * Release Connection
     *
     * @param bool $force
     */
    public function release(bool $force = false): void
    {
        if ($this->release) {
            $this->release = false;
            $this->pool->release($this);
        }
    }
}