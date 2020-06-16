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
 * Class LoaderInterface
 *
 * @since 2.0
 */
interface LoaderInterface
{
    /**
     * Disable or enable this component.
     *
     * @return bool
     * @since 2.0.7
     */
    public function isEnable(): bool;

    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array;
}
