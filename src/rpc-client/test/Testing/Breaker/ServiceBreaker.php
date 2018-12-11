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
namespace SwoftTest\RpcClient\Testing\Breaker;

use Swoft\Bean\Annotation\Value;
use Swoft\Sg\Bean\Annotation\Breaker;
use Swoft\Sg\Circuit\CircuitBreaker;

/**
 * the breaker of default
 *
 * @Breaker("breaker")
 */
class ServiceBreaker extends CircuitBreaker
{
    /**
     * @var string 服务名称
     */
    public $serviceName = 'breakerService';

    /**
     * The number of successive failures
     * If the arrival, the state switch to open
     *
     * @Value(name="${config.breaker.default.failCount}")
     * @var int
     */
    protected $switchToFailCount = 3;

    /**
     * The number of successive successes
     * If the arrival, the state switch to close
     *
     * @Value(name="${config.breaker.default.successCount}")
     * @var int
     */
    protected $switchToSuccessCount = 3;

    /**
     * Switch close to open delay time
     * The unit is milliseconds
     *
     * @Value(name="${config.breaker.default.delayTime}")
     * @var int
     */
    protected $delaySwitchTimer = 500;
}
