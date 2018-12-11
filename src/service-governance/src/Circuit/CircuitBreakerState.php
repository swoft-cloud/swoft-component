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

/**
 * circuit breaker - close open half-open
 */
abstract class CircuitBreakerState
{
    /**
     * @var CircuitBreaker circuit breaker
     */
    protected $circuitBreaker;

    /**
     * CircuitBreakerState constructor.
     *
     * @param CircuitBreaker $circuitBreaker circuit breaker
     */
    public function __construct(CircuitBreaker $circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
        $this->circuitBreaker->initCounter();
    }

    /**
     * @param callable|array|mixed $callback
     * @param array $params
     * @param null $fallback
     * @return mixed
     */
    abstract public function doCall($callback, array $params = [], $fallback = null);

    /**
     * circuit breaker service name
     *
     * @return string
     */
    protected function getServiceName(): string
    {
        return $this->circuitBreaker->serviceName;
    }
}
