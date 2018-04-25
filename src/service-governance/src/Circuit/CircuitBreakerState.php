<?php

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
     * circuit breaker service name
     *
     * @return string
     */
    protected function getServiceName(): string
    {
        return $this->circuitBreaker->serviceName;
    }

    /**
     * @param callable|array|mixed $callback
     * @param array $params
     * @param null $fallback
     * @return mixed
     */
    abstract public function doCall($callback, array $params = [], $fallback = null);
}
