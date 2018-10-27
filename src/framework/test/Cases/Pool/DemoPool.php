<?php
namespace SwoftTest\Pool;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Pool\ConnectionInterface;
use Swoft\Pool\ConnectionPool;
use Swoft\Pool\PoolProperties;
use SwoftTest\Connection\DemoConnection;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;

/**
 * Class DemoPool
 * @Pool()
 * @package SwoftTest\Pool
 */
class DemoPool extends ConnectionPool
{
    /**
     * The config of poolbPool
     *
     * @Inject()
     *
     * @var DemoPoolConfig
     */
    protected $poolConfig;

    public function createConnection(): ConnectionInterface
    {
        return new DemoConnection($this);
    }
}