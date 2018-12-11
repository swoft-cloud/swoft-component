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
namespace SwoftTest\Redis\Testing\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Redis\Pool\Config\RedisPoolConfig;

/**
 * Class TimeoutPoolConfig
 * @Bean
 * @package SwoftTest\Redis\Testing\Pool\Config
 */
class TimeoutPoolConfig extends RedisPoolConfig
{
    /**
     * the time of connect timeout
     * @var int
     */
    protected $timeout = 1;

    /**
     * the addresses of connection
     * @var array
     */
    protected $uri = [
        'echo.swoft.org:6379'
    ];
}
