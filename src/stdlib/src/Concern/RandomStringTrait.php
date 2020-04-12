<?php declare(strict_types=1);

namespace Swoft\Stdlib\Concern;

use Exception;
use RuntimeException;
use Swoft\Stdlib\Helper\EnvHelper;
use function base_convert;
use function bin2hex;
use function ceil;
use function microtime;
use function random_bytes;
use function random_int;
use function substr;

/**
 * Trait RandomStringTrait
 *
 * @since 2.0.7
 */
trait RandomStringTrait
{
    /**
     * @param string $prefix
     * @param bool   $moreEntropy
     *
     * @return string
     */
    public static function uniqID(string $prefix = '', bool $moreEntropy = false): string
    {
        // If on Cygwin, $moreEntropy must be True.
        if (EnvHelper::isCygwin()) {
            $moreEntropy = true;
        }

        if (false === $moreEntropy) {
            return uniqid($prefix, false);
        }

        return str_replace('.', '', uniqid($prefix, true));
    }

    /**
     * @param string $prefix
     * @param bool   $moreEntropy
     *
     * @return string
     */
    public static function getUniqid(string $prefix = '', bool $moreEntropy = false): string
    {
        // If on Cygwin, $moreEntropy must be TRUE.
        if (EnvHelper::isCygwin()) {
            $moreEntropy = true;
        }

        $uniqId = uniqid($prefix, $moreEntropy);
        $uniqId = str_replace('.', '', $uniqId);

        return $uniqId;
    }

    /**
     * @param string $prefix
     *
     * @return string eg: "e6d7ce8a6de"
     */
    public static function microTimeId(string $prefix = ''): string
    {
        $micro = microtime(true) * 10000;

        return $prefix . base_convert($micro, 10, 16);
    }

    /**
     * Use the instead of uniqid()
     *
     * @param int $length
     *
     * @return string
     * @throws Exception
     * @link https://www.php.net/manual/zh/function.uniqid.php#120123
     */
    public static function uniqIdReal(int $length = 13): string
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        $bytes = random_bytes((int)ceil($length / 2));

        return (string)substr(bin2hex($bytes), 0, $length);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param int $length
     *
     * @return string
     * @throws RuntimeException
     * @throws Exception
     */
    public static function random(int $length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size  = $length - $len;
            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * Generate a more truly "random" bytes.
     *
     * @param int $length
     *
     * @return string
     * @throws Exception
     * @deprecated since version 5.2. Use random_bytes instead.
     */
    public static function randomBytes(int $length = 16): string
    {
        if (PHP_MAJOR_VERSION >= 7 || defined('RANDOM_COMPAT_READ_BUFFER')) {
            $bytes = random_bytes($length);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $bytes = openssl_random_pseudo_bytes($length, $strong);

            if ($bytes === false || $strong === false) {
                throw new RuntimeException('Unable to generate random string.');
            }
        } else {
            throw new RuntimeException('OpenSSL extension or paragonie/random_compat is required for PHP 5 users.');
        }

        return $bytes;
    }

    /**
     * Generates a random string of a given type and length. Possible
     * values for the first argument ($type) are:
     *  - alnum    - alpha-numeric characters (including capitals)
     *  - alpha    - alphabetical characters (including capitals)
     *  - hexdec   - hexadecimal characters, 0-9 plus a-f
     *  - numeric  - digit characters, 0-9
     *  - nozero   - digit characters, 1-9
     *  - distinct - clearly distinct alpha-numeric characters.
     * For values that do not match any of the above, the characters passed
     * in will be used.
     * ##### Example
     *     echo Str::random('alpha', 20);
     *     // Output:
     *     DdyQFCddSKeTkfjCewPa
     *     echo Str::random('distinct', 20);
     *     // Output:
     *     XCDDVXV7FUSYAVXFFKSL
     *
     * @param string  $type   A type of pool, or a string of characters to use as the pool
     * @param integer $length Length of string to return
     *
     * @return  string
     * @throws Exception
     */
    public static function randomString($type = 'alnum', $length = 8): string
    {
        $utf8 = false;

        switch ($type) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'lowalnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
            default:
                $pool = (string)$type;
                $utf8 = !self::isAscii($pool);
                break;
        }

        // Split the pool into an array of characters
        $pool = ($utf8 === true) ? self::strSplit($pool, 1) : str_split($pool, 1);

        // Largest pool key
        $max = count($pool) - 1;

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            // Select a random character from the pool and add it to the string
            $str .= $pool[random_int(0, $max)];
        }

        // Make sure alnum strings contain at least one letter and one digit
        if ($type === 'alnum' && $length > 1) {
            if (ctype_alpha($str)) {
                // Add a random digit
                $str[random_int(0, $length - 1)] = chr(random_int(48, 57));
            } elseif (ctype_digit($str)) {
                // Add a random letter
                $str[random_int(0, $length - 1)] = chr(random_int(65, 90));
            }
        }

        return $str;
    }

    /**
     * Create a simple random token-string
     *
     * @param integer $length Length of string
     * @param string  $salt
     *
     * @return  string  Generated token
     * @throws Exception
     */
    public static function randomToken(int $length = 24, string $salt = ''): string
    {
        $string = '';
        $chars  = '0456789abc1def2ghi3jkl';
        $maxVal = strlen($chars) - 1;

        for ($i = 0; $i < $length; ++$i) {
            $string .= $chars[random_int(0, $maxVal)];
        }

        return md5($string . $salt);
    }

    /**
     * Generate a "random" alpha-numeric string.
     * Should not be considered sufficient for cryptography, etc.
     *
     * @param int $length
     *
     * @return string
     */
    public static function quickRandom(int $length = 16): string
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * @param int $randMin
     * @param int $randMax
     *
     * @return string
     * @throws Exception
     */
    public static function timeUniId(int $randMin = 100, int $randMax = 999): string
    {
        $mt = str_replace('.', '', microtime(true));

        return base_convert($mt . random_int($randMin, $randMax), 10, 16);
    }
}
