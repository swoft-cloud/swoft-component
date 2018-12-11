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
namespace SwoftTest\Redis\Testing\Clients;

use Swoft\Bean\Annotation\Bean;
use Swoft\Redis\Redis;
use SwoftTest\Redis\Testing\Pool\TimeoutPool;

/**
 * Class TimeoutRedis
 * @Bean
 * @package SwoftTest\Redis\Testing\Clients
 */
class TimeoutRedis extends Redis
{
    /**
     * @var string
     */
    protected $poolName = TimeoutPool::class;
}
