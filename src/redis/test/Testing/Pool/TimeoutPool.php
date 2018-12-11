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
namespace SwoftTest\Redis\Testing\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Swoft\Redis\Pool\RedisPool;
use SwoftTest\Redis\Testing\Pool\Config\TimeoutPoolConfig;

/**
 * Class TimeoutPool
 * @Pool
 * @package SwoftTest\Redis\Testing\Pool
 */
class TimeoutPool extends RedisPool
{
    /**
     * Config
     *
     * @Inject
     * @var TimeoutPoolConfig
     */
    protected $poolConfig;
}
