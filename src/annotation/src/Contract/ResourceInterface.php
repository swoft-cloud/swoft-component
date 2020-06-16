<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Annotation\Contract;

/**
 * Class ResourceInterface
 *
 * @since 2.0
 */
interface ResourceInterface
{
    /**
     * Load annotation resource
     */
    public function load(): void;
}
