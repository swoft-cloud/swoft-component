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
 * Half-open state and switching
 *
 * 1. Reset failCounter=0
 * 2. Reset successCounter=0
 * 3. Operation success counter
 * 4. Operation fail counter
 * 5. Successive operation succeeds a certain number of times, switching to the close state
 * 6. Continuous operation failed a certain number of times, switch to open
 * 7. Only one request is executed during the same concurrent time
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

            if (
                ($class instanceof Client && $class->isConnected() == false) ||
                ($class instanceof ConnectionInterface && $class->check() == false)
            ) {
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
