<?php declare(strict_types=1);

namespace Swoft\Stdlib\Helper;

use function explode;
use function function_exists;
use function is_array;
use function is_object;
use function is_string;
use function method_exists;
use function ob_get_clean;
use function ob_start;
use const PHP_EOL;
use function preg_replace;
use function strpos;
use function var_dump;
use function var_export;

/**
 * Php helper
 *
 * @since 2.0
 */
class PhpHelper
{
    /**
     * Call by callback
     *
     * @param callable $cb   callback
     * @param array    $args arguments
     *
     * @return mixed
     */
    public static function call($cb, ...$args)
    {
        if (is_string($cb)) {
            // className::method
            if (strpos($cb, '::') > 0) {
                $cb = explode('::', $cb, 2);
                // function
            } elseif (function_exists($cb)) {
                return $cb(...$args);
            }
        } elseif (is_object($cb) && method_exists($cb, '__invoke')) {
            return $cb(...$args);
        }

        if (is_array($cb)) {
            [$obj, $mhd] = $cb;

            return is_object($obj) ? $obj->$mhd(...$args) : $obj::$mhd(...$args);
        }

        return $cb(...$args);
    }

    /**
     * Call by callback
     *
     * @param callable $cb
     * @param array    $args
     *
     * @return mixed
     */
    public static function callByArray($cb, array $args = [])
    {
        return self::call($cb, ...$args);
    }


    /**
     * dump vars
     *
     * @param array ...$args
     *
     * @return string
     */
    public static function dumpVars(...$args): string
    {
        ob_start();
        var_dump(...$args);
        $string = ob_get_clean();

        return preg_replace("/=>\n\s+/", '=> ', $string);
    }

    /**
     * print vars
     *
     * @param array ...$args
     *
     * @return string
     */
    public static function printVars(...$args): string
    {
        $string = '';

        foreach ($args as $arg) {
            $string .= print_r($arg, 1) . PHP_EOL;
        }

        return preg_replace("/Array\n\s+\(/", 'Array (', $string);
    }

    /**
     * @param mixed $var
     *
     * @return string
     */
    public static function exportVar($var): string
    {
        $string = var_export($var, true);

        return preg_replace('/=>\s+\n\s+array \(/', '=> array (', $string);
    }
}
