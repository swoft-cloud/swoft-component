<?php

namespace Swoft\Sg\Circuit;

use Swoft\App;
use Swoole\Coroutine\Client;

/**
 * 半开状态及切换(half-open)
 *
 * 1. 重置failCounter=0
 * 2. 重置successCounter=0
 * 3. 操作成功successCounter计数
 * 4. 操作失败failCounter计数
 * 5. 连续操作成功一定计数, 切换为close状态
 * 6. 连续操作失败一定计数, 切换为open
 * 7. 同一并发时间只有一个请求执行
 */
class HalfOpenState extends CircuitBreakerState
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
        // Lock
        $lock = $this->circuitBreaker->getHalfOpenLock();
        $lock->lock();
        list($class, $method) = $callback;

        try {
            if (!$class) {
                throw new \RuntimeException($this->getServiceName() . ' service, connection establishment failed(null)');
            }

            if ($class instanceof Client && $class->isConnected() === false) {
                throw new \RuntimeException($this->circuitBreaker->serviceName . ' service, current connection has been disconnected');
            }

            $data = $class->$method(...$params);
            $this->circuitBreaker->incSuccessCount();
            App::trace($this->getServiceName() . ' service, current[half-open], Try to callback');
        } catch (\Exception $e) {
            $this->circuitBreaker->incFailCount();
            $data = $this->circuitBreaker->fallback($fallback);
            App::error($this->getServiceName() . ' service, current[half-open], Try to fallback, error=' . $e->getMessage());
        }

        $failCount = $this->circuitBreaker->getFailCounter();
        $successCount = $this->circuitBreaker->getSuccessCounter();
        $switchToFailCount = $this->circuitBreaker->getSwitchToFailCount();
        $switchToSuccessCount = $this->circuitBreaker->getSwitchToSuccessCount();

        if ($failCount >= $switchToFailCount && $this->circuitBreaker->isHalfOpen()) {
            $this->circuitBreaker->switchToOpenState();
            App::trace($this->getServiceName() . ' service, current[half-open], Failed to reach the limit, start switching to on');
        }

        if ($successCount >= $switchToSuccessCount) {
            $this->circuitBreaker->switchToCloseState();
            App::trace($this->getServiceName() . ' service, current[half-open], Maximum number of successes reached, service has been restored, start switching to off');
        }

        // Release lock
        $lock->unlock();

        App::trace($this->getServiceName() . ' service, current[half-open], failCount=' . $failCount . ' successCount=' . $successCount);

        return $data;
    }
}
