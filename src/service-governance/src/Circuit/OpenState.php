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

/**
 * Open state and switch(open)
 *
 * 1. reset failCounter=0 successCounter=0
 * 2. The request immediately returns an error response
 * 3. The timer switches to the half-open state after a certain period of time(open)
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

        App::trace($this->getServiceName() . ' service，current[open]，exec fallback handle');
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
