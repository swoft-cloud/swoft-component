<?php

namespace Swoft\Sg\Circuit;

use Swoft\App;
use Swoole\Coroutine\Client;

/**
 * 关闭状态及切换(close)
 *
 * 1. 重置failCounter=0 successCount=0
 * 2. 操作失败, failCounter计数
 * 3. 操作失败一定计数, 切换为open开启状态
 */
class CloseState extends CircuitBreakerState
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
        list($class, $method) = $callback;

        try {
            if (!$class) {
                throw new \RuntimeException($this->circuitBreaker->serviceName . 'service, connection establishment failed(null)');
            }

            if ($class instanceof Client && $class->isConnected() === false) {
                throw new \RuntimeException($this->circuitBreaker->serviceName . 'service, The current connection has been disconnected');
            }
            $data = $class->$method(...$params);
        } catch (\Exception $e) {
            if ($this->circuitBreaker->isClose()) {
                $this->circuitBreaker->incFailCount();
            }

            App::error($this->circuitBreaker->serviceName . 'service, current[close], Service call failed, Start service downgrade fault tolerance, error=' . $e->getMessage());
            $data = $this->circuitBreaker->fallback($fallback);
        }

        $failCount = $this->circuitBreaker->getFailCounter();
        $switchToFailCount = $this->circuitBreaker->getSwitchToFailCount();
        if ($failCount >= $switchToFailCount && $this->circuitBreaker->isClose()) {
            App::trace($this->circuitBreaker->serviceName . 'service, current[close], service failed to reach the limit, Start switching to on, failCount=' . $failCount);
            $this->circuitBreaker->switchToOpenState();
        }

        App::trace($this->circuitBreaker->serviceName . 'service, current[close], failCount=' . $this->circuitBreaker->getFailCounter());
        return $data;
    }
}
