<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Contract;

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

    /**
     * Before dispatch
     *
     * @param array $params
     */
    public function before(...$params): void;

    /**
     * After dispatch
     *
     * @param array $params
     */
    public function after(...$params): void;
}
