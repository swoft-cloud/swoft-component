<?php declare(strict_types=1);

namespace Swoft;

use function array_merge;
use Swoft\Contract\DispatcherInterface;

/**
 * Class Dispatcher
 * @since 2.0
 */
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
     * @var array
     */
    protected $preMiddlewares = [];

    /**
     * @var array
     */
    protected $afterMiddlewares = [];

    /**
     * Init
     */
    public function init(): void
    {
        $this->preMiddlewares   = array_merge($this->preMiddleware(), $this->preMiddlewares);
        $this->afterMiddlewares = array_merge($this->afterMiddleware(), $this->afterMiddlewares);
    }

    /**
     * Request middleware
     *
     * @return array
     */
    public function requestMiddleware(): array
    {
        return $this->middlewares ?
            array_merge($this->preMiddlewares, $this->middlewares, $this->afterMiddlewares) :
            array_merge($this->preMiddlewares, $this->afterMiddlewares);
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

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
