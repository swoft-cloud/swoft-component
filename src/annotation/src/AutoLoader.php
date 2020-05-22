<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Annotation;

use Swoft\Annotation\Contract\LoaderInterface;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader implements LoaderInterface
{
    /**
     * Disable or enable this component.
     *
     * @return bool
     * @since 2.0.7
     */
    public function isEnable(): bool
    {
        return true;
    }

    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }
}
