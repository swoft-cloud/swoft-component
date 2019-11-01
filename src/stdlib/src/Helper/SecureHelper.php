<?php declare(strict_types=1);

namespace Swoft\Stdlib\Helper;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use function function_exists;
use function mb_substr;

/**
 * Class SecureHelper
 *
 * @link https://github.com/ezimuel/PHP-Secure-Session/
 */
class SecureHelper
{
    /**
     * Encrypt and authenticate
     *
     * @param string $data
     * @param string $key
     *
     * @return string
     * @throws Exception
     */
    public static function encrypt(string $data, string $key): string
    {
        $iv = random_bytes(16); // AES block size in CBC mode

        if (!function_exists('openssl_encrypt')) {
            throw new RuntimeException('ext openssl is required');
        }

        // Encryption
        /** @noinspection PhpComposerExtensionStubsInspection */
        $cipherText = openssl_encrypt($data, 'AES-256-CBC', mb_substr($key, 0, 32, '8bit'), OPENSSL_RAW_DATA, $iv);

        // Authentication
        $hmac = hash_hmac('SHA256', $iv . $cipherText, mb_substr($key, 32, null, '8bit'), true);

        return $hmac . $iv . $cipherText;
    }

    /**
     * Authenticate and decrypt
     *
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    public static function decrypt(string $data, string $key): string
    {
        if (!function_exists('openssl_encrypt')) {
            throw new RuntimeException('ext openssl is required');
        }

        $hmac = mb_substr($data, 0, 32, '8bit');
        $iv   = mb_substr($data, 32, 16, '8bit');

        $cipherText = mb_substr($data, 48, null, '8bit');

        // Authentication
        $hmacNew = hash_hmac('SHA256', $iv . $cipherText, mb_substr($key, 32, null, '8bit'), true);

        if (!hash_equals($hmac, $hmacNew)) {
            throw new InvalidArgumentException('Authentication failed');
        }

        // Decrypt
        /** @noinspection PhpComposerExtensionStubsInspection */
        return openssl_decrypt($cipherText, 'AES-256-CBC', mb_substr($key, 0, 32, '8bit'), OPENSSL_RAW_DATA, $iv);
    }

}
