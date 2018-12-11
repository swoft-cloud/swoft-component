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
namespace Swoft\Contract;

/**
 * Dispatcher
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
