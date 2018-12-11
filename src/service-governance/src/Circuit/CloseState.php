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
use Swoft\Pool\ConnectionInterface;
use Swoole\Coroutine\Client;

/**
 * Closed state and switch(close)
 *
 * 1. Reset failCounter=0 successCount=0
 * 2. Operation failed, failCounter
 * 3. The operation failed a certain number of times, switching to the open state
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

            if (
                ($class instanceof Client && $class->isConnected() == false) ||
                ($class instanceof ConnectionInterface && $class->check() == false)
            ) {
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
