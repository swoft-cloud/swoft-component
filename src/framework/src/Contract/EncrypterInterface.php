<?php declare(strict_types=1);

namespace Swoft\Contract;

/**
 * Class EncrypterInterface
 *
 * @since 2.0.7
 */
interface EncrypterInterface
{
    /**
     * Encrypt data
     *
     * @param string $data
     *
     * @return string
     */
    public static function encrypt(string $data): string;

    /**
     * Decrypt data
     *
     * @param string $data
     *
     * @return string
     */
    public static function decrypt(string $data): string;
}
