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
namespace SwoftTest\RpcClient\Testing\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Pool\PoolProperties;

/**
 * Class DemoServicePoolConfig
 * @Bean
 * @package SwoftTest\Rpc\Client\Testing\Pool\Config
 */
class DemoServicePoolConfig extends PoolProperties
{
    protected $name = 'service.demo';

    protected $uri = [
        '127.0.0.1:8099'
    ];

    /**
     * Connection timeout
     *
     * @var int
     */
    protected $timeout = 1;
}
