<?php declare(strict_types=1);

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
