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
namespace SwoftTest\Testing\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Swoft\Pool\ConnectionInterface;
use Swoft\Pool\ConnectionPool;
use SwoftTest\Testing\Connection\DemoConnection;

/**
 * Class DemoPool
 * @Pool
 * @package SwoftTest\Pool
 */
class DemoPool extends ConnectionPool
{
    /**
     * The config of poolbPool
     *
     * @Inject
     *
     * @var DemoPoolConfig
     */
    protected $poolConfig;

    public function createConnection(): ConnectionInterface
    {
        return new DemoConnection($this);
    }
}
