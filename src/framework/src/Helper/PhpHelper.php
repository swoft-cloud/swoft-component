<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Helper;

/**
 * Class PhpHelper
 *
 * @package Swoft\Helper
 */
class PhpHelper
{
    /**
     * is Cli Enviroment
     *
     * @return  boolean
     */
    public static function isCli(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * Is Mac Enviroment
     *
     * @return bool
     */
    public static function isMac(): bool
    {
        return \stripos(PHP_OS, 'Darwin') !== false;
    }

    /**
     * @param mixed $callback callback
     * @param array $args
     * @return mixed
     */
    public static function call($callback, array $args = [])
    {
        if (\is_object($callback) || (\is_string($callback) && \function_exists($callback))) {
            return $callback(...$args);
        } elseif (\is_array($callback)) {
            list($obj, $method) = $callback;
            return \is_object($obj) ? $obj->$method(...$args) : $obj::$method(...$args);
        }
        return $callback(...$args);
    }
}
