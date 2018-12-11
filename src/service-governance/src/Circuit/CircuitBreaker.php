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
namespace Swoft\Sg\Circuit;

use Swoft\App;
use Swoole\Lock;

/**
 * Class CircuitBreaker
 */
class CircuitBreaker
{
    /**
     * Close state
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
     * @var int Bad request counter
     */
    public $failCounter = 0;

    /**
     * @var int Successful request counter
     */
    public $successCounter = 0;

    /**
     * @var int Switching Open state to Half Open state
     */
    public $switchOpenToHalfOpenTime = 0;

    /**
     * @var string Service name
     */
    public $serviceName = 'breakerService';

    /**
     * @var \Swoole\Lock Lock of half open state
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
     * @var CircuitBreakerState Current circuit breaker state
     */
    private $circuitState;

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
     * Circuit call method
     *
     * @param mixed $callback Callback
     * @param array $params   parameters
     * @param mixed $fallback Fallback callback
     *
     * @return mixed
     */
    public function call($callback, array $params = [], $fallback = null)
    {
        return $this->circuitState->doCall($callback, $params, $fallback);
    }

    /**
     * Increment Failure count
     */
    public function incFailCount()
    {
        $this->failCounter++;
    }

    /**
     * Increment Success count
     */
    public function incSuccessCount()
    {
        $this->successCounter++;
    }

    /**
     * Is it close state
     *
     * @return bool
     */
    public function isClose(): bool
    {
        return $this->circuitState instanceof CloseState;
    }

    /**
     * Is it open state
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->circuitState instanceof OpenState;
    }

    /**
     * Is half open state
     *
     * @return bool
     */
    public function isHalfOpen(): bool
    {
        return $this->circuitState instanceof HalfOpenState;
    }

    /**
     * Switch to close state
     */
    public function switchToCloseState()
    {
        App::debug($this->serviceName . 'service, current[' . $this->getCurrentState() . '], Fuse state switching, switch to [close]');
        $this->circuitState = new CloseState($this);
    }

    /**
     * Switch to open state
     */
    public function switchToOpenState()
    {
        App::debug($this->serviceName . 'service, current[' . $this->getCurrentState() . '], Fuse state switching, switch to [open]');
        $this->circuitState = new OpenState($this);
    }

    /**
     * Switch to half open state
     */
    public function switchToHalfState()
    {
        App::debug($this->serviceName . 'service, current[' . $this->getCurrentState() . '], Fuse state switching, switch to [half]');

        $this->circuitState = new HalfOpenState($this);
    }

    /**
     * Fallback processing
     *
     * @param mixed $fallback
     * @param array $params
     *
     * @return null
     */
    public function fallback($fallback = null, array $params = [])
    {
        if ($fallback === null) {
            App::debug($this->serviceName . ' service, current[' . $this->getCurrentState() . '], service fallback processing, fallback undefined');
            return null;
        }

        if (\is_array($fallback) && \count($fallback) === 2) {
            list($className, $method) = $fallback;
            App::debug($this->serviceName . ' service, service fallback processing, exec fallback, class=' . $className . ' method=' . $method);

            return $fallbackObj->$method(...$params);
        }

        return null;
    }

    /**
     * Current state
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
     * Get the failure counter
     *
     * @return int
     */
    public function getFailCounter(): int
    {
        return $this->failCounter;
    }

    /**
     * Get the successful counter
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
     * Get the lock of Half open state
     *
     * @return \Swoole\Lock
     */
    public function getHalfOpenLock(): Lock
    {
        return $this->halfOpenLock;
    }
}
