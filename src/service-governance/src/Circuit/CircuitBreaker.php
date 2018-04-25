<?php

namespace Swoft\Sg\Circuit;

use Swoft\App;
use Swoole\Lock;

/**
 * Class CircuitBreaker
 */
class CircuitBreaker
{
    /**
     * Disabled
     */
    const CLOSE = 'close';

    /**
     * Open state
     */
    const OPEN = 'open';

    /**
     * Half open
     */
    const HALF_OPEN_STATE = 'halfOpenState';

    /**
     * Uninitialized
     */
    const PENDING = 'pending';

    /**
     * @var int Bad request count
     */
    public $failCounter = 0;

    /**
     * @var int Successful request count
     */
    public $successCounter = 0;


    /**
     * @var int Switching on state to half open state
     */
    public $switchOpenToHalfOpenTime = 0;

    /**
     * @var string service name
     */
    public $serviceName = 'breakerService';

    /**
     * @var CircuitBreakerState 熔断器状态，开启、半开、关闭
     */
    private $circuitState;

    /**
     * @var \Swoole\Lock 半开状态锁
     */
    protected $halfOpenLock;

    /**
     * The number of successive failures
     * If the arrival, the state switch to open
     *
     * @var int
     */
    protected $switchToFailCount = 6;

    /**
     * The number of successive successes
     * If the arrival, the state switch to close
     *
     * @var int
     */
    protected $switchToSuccessCount = 6;

    /**
     * Switch close to open delay time
     * The unit is milliseconds
     *
     * @var int
     */
    protected $delaySwitchTimer = 5000;

    /**
     * Initialization
     */
    public function init()
    {
        // State initialization
        $this->circuitState = new CloseState($this);
        $this->halfOpenLock = new Lock(SWOOLE_MUTEX);
    }

    /**
     * 熔断器调用
     *
     * @param mixed $callback 回调函数
     * @param array $params   参数
     * @param mixed $fallback 失败回调
     *
     * @return mixed 返回结果
     */
    public function call($callback, array $params = [], $fallback = null)
    {
        return $this->circuitState->doCall($callback, $params, $fallback);
    }

    /**
     * Failure count
     */
    public function incFailCount()
    {
        $this->failCounter++;
    }

    /**
     * Success count
     */
    public function incSuccessCount()
    {
        $this->successCounter++;
    }

    /**
     * Is it off
     *
     * @return bool
     */
    public function isClose(): bool
    {
        return $this->circuitState instanceof CloseState;
    }

    /**
     * Is it on
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->circuitState instanceof OpenState;
    }

    /**
     * 是否是半开状态
     *
     * @return bool
     */
    public function isHalfOpen(): bool
    {
        return $this->circuitState instanceof HalfOpenState;
    }

    /**
     * Switch to off
     */
    public function switchToCloseState()
    {
        App::debug($this->serviceName . '服务，当前[' . $this->getCurrentState() . ']，熔断器状态切换，切换到[关闭]状态');
        $this->circuitState = new CloseState($this);
    }

    /**
     * Switch to on
     */
    public function switchToOpenState()
    {
        App::debug($this->serviceName . '服务，当前[' . $this->getCurrentState() . ']，熔断器状态切换，切换到[开启]状态');
        $this->circuitState = new OpenState($this);
    }

    /**
     * 切换到半开
     */
    public function switchToHalfState()
    {
        App::debug($this->serviceName . '服务，当前[' . $this->getCurrentState() . ']，熔断器状态切换，切换到[半开]状态');

        $this->circuitState = new HalfOpenState($this);
    }

    /**
     * 降级处理
     *
     * @param mixed $fallback
     * @param array $params
     *
     * @return null
     */
    public function fallback($fallback = null, array $params = [])
    {
        if ($fallback === null) {
            App::debug($this->serviceName . '服务，当前[' . $this->getCurrentState() . ']，服务降级处理，fallback未定义');
            return null;
        }

        if (\is_array($fallback) && \count($fallback) === 2) {
            list($className, $method) = $fallback;
            App::debug($this->serviceName . '服务，服务降级处理，执行fallback, class=' . $className . ' method=' . $method);

            return $className->$method(...$params);
        }

        return null;
    }

    /**
     * Current status
     *
     * @return string
     */
    public function getCurrentState(): string
    {
        if ($this->circuitState instanceof CloseState) {
            return self::CLOSE;
        }
        if ($this->circuitState instanceof HalfOpenState) {
            return self::HALF_OPEN_STATE;
        }

        if ($this->circuitState instanceof OpenState) {
            return self::OPEN;
        }
        return self::PENDING;
    }

    /**
     * Initialization count
     */
    public function initCounter()
    {
        $this->failCounter = 0;
        $this->successCounter = 0;
    }

    /**
     * Get the failure count
     *
     * @return int
     */
    public function getFailCounter(): int
    {
        return $this->failCounter;
    }

    /**
     * Get success count
     *
     * @return int
     */
    public function getSuccessCounter(): int
    {
        return $this->successCounter;
    }

    /**
     * Get started to switch to failed count
     *
     * @return int
     */
    public function getSwitchToFailCount(): int
    {
        return $this->switchToFailCount;
    }

    /**
     * Start switching to a successful count
     *
     * @return int
     */
    public function getSwitchToSuccessCount(): int
    {
        return $this->switchToSuccessCount;
    }

    /**
     * Get open switch to half open time
     *
     * @return int
     */
    public function getSwitchOpenToHalfOpenTime(): int
    {
        return $this->switchOpenToHalfOpenTime;
    }

    /**
     * 初始化开启切换到半开的时间
     *
     * @param int $switchOpenToHalfOpenTime
     */
    public function setSwitchOpenToHalfOpenTime(int $switchOpenToHalfOpenTime)
    {
        $this->switchOpenToHalfOpenTime = $switchOpenToHalfOpenTime;
    }

    /**
     * 关闭切换到开启延迟定时器时间
     *
     * @return int
     */
    public function getDelaySwitchTimer(): int
    {
        return $this->delaySwitchTimer;
    }

    /**
     * 半开状态锁
     *
     * @return \Swoole\Lock
     */
    public function getHalfOpenLock(): Lock
    {
        return $this->halfOpenLock;
    }
}
