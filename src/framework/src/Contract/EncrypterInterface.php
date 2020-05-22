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
