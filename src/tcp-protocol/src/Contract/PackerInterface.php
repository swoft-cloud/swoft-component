<?php declare(strict_types=1);

namespace Swoft\Tcp\Protocol\Contract;

use Swoft\Tcp\Protocol\Package;

/**
 * Interface PackerInterface - Data packer interface
 *
 * @since 2.0.3
 */
interface PackerInterface
{
    /**
     * @return string
     */
    public static function getType(): string;

    /**
     * Encode Package object to string data.
     *
     * @param Package $package
     *
     * @return string
     */
    public function encode(Package $package): string;

    /**
     * Decode tcp package data to Package object
     *
     * @param string $data package data
     *
     * @return Package
     */
    public function decode(string $data): Package;
}
