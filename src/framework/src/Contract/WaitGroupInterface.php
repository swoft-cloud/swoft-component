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
 * Class WaitGroupInterface
 *
 * @since 2.0
 */
interface WaitGroupInterface
{
    /**
     * Add task
     */
    public function add(): void;

    /**
     * Done task
     */
    public function done(): void;

    /**
     * Wait task
     */
    public function wait(): void;
}
