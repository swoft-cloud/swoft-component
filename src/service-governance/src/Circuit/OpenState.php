<?php

namespace Swoft\Sg\Circuit;

use Swoft\App;

/**
 * 开启状态及切换(open)
 *
 * 1. 重置failCounter=0 successCounter=0
 * 2. 请求立即返回错误响应
 * 3. 定时器一定时间后切换为半开状态(open)
 */
class OpenState extends CircuitBreakerState
{
    /**
     * call circuit breaker
     *
     * @param mixed $callback Callback
     * @param array $params Parameters
     * @param mixed $fallback Fallback callback
     *
     * @return mixed
     */
    public function doCall($callback, array $params = [], $fallback = null)
    {
        $data = $this->circuitBreaker->fallback();

        App::trace($this->getServiceName() . ' service，current[open]，exec fallback 服务降级容错处理');
        // Turn on timer
        $nowTime = time();

        if ($this->circuitBreaker->isOpen()
            && $nowTime > $this->circuitBreaker->getSwitchOpenToHalfOpenTime()
        ) {
            $delayTime = $this->circuitBreaker->getDelaySwitchTimer();

            // 定时器不是严格的，新增3s,容错时间
            $switchToHalfStateTime = $nowTime + ($delayTime / 1000) + 3;
            App::getTimer()->addAfterTimer('openState', $delayTime, [$this, 'delayCallback']);
            $this->circuitBreaker->setSwitchOpenToHalfOpenTime($switchToHalfStateTime);

            App::trace(
                $this->getServiceName() . ' service，current[open]，Create delay trigger，After a period of time, the state is switched to the half-open state'
            );
        }

        return $data;
    }

    /**
     * Delayed execution of timer
     */
    public function delayCallback()
    {
        if ($this->circuitBreaker->isOpen()) {
            App::debug($this->getServiceName() . ' service,current[open]，Delay trigger triggered，Ready to start switching to half open');
            $this->circuitBreaker->switchToHalfState();
        }
    }
}
