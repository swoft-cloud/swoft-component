<?php declare(strict_types=1);


namespace Swoft;


abstract class Dispatcher implements DispatcherInterface
{
    /**
     * User defined middlewares
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * Default middleware name
     *
     * @var string
     */
    protected $defaultMiddleware;

    /**
     * Request middleware
     *
     * @return array
     */
    public function requestMiddleware(): array
    {
        return \array_merge($this->preMiddleware(), $this->middlewares, $this->afterMiddleware());
    }

    /**
     * Pre middleware
     *
     * @return array
     */
    public function preMiddleware(): array
    {
        return [];
    }

    /**
     * After middleware
     *
     * @return array
     */
    public function afterMiddleware(): array
    {
        return [];
    }

    /**
     * Before dispatcher
     *
     * @param array ...$params
     */
    public function before(...$params): void
    {
    }

    /**
     * After dispatcher
     *
     * @param array ...$params
     */
    public function after(...$params): void
    {
    }
}