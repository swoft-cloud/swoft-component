<?php declare(strict_types=1);


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
