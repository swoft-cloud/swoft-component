<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Testing;

use function implode;

/**
 * Class TempString - This is an tool class for testing.
 */
class TempString
{
    /**
     * @var string
     */
    private static $string = '';

    /**
     * @return string
     */
    public static function get(): string
    {
        return self::$string;
    }

    /**
     * @param string $string
     */
    public static function set(string $string): void
    {
        self::$string = $string;
    }

    /**
     * @param string[] $string
     */
    public static function add(string ...$string): void
    {
        self::$string .= implode(' ', $string);
    }

    /**
     * @param string $string
     */
    public static function append(string $string): void
    {
        self::$string = $string . self::$string;
    }

    /**
     * @param string $string
     */
    public static function prepend(string $string): void
    {
        self::$string .= $string;
    }

    /**
     * Clear string
     *
     * @return string
     */
    public static function clear(): string
    {
        $old = self::$string;

        self::$string = '';

        return $old;
    }

    /**
     * Clear string
     *
     * @return string
     */
    public static function reset(): string
    {
        return self::clear();
    }
}
