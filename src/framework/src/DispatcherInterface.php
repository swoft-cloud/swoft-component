<?php declare(strict_types=1);


namespace Swoft;

/**
 * Class DispatcherInterface
 *
 * @since 2.0
 */
interface DispatcherInterface
{
    /**
     * Dispatch
     *
     * @param array ...$params dispatcher params
     */
    public function dispatch(...$params);

    /**
     * Request middleware
     *
     * @return array
     */
    public function requestMiddleware(): array;

    /**
     * Pre middleware
     *
     * @return array
     */
    public function preMiddleware(): array;

    /**
     * After middleware
     *
     * @return array
     */
    public function afterMiddleware(): array;
}