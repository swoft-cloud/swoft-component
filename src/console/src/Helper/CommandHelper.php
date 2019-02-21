<?php

namespace Swoft\Console\Helper;

/**
 * Class CommandHelper
 * @package Swoft\Console\Helper
 */
class CommandHelper
{
    /**
     * Get controller prefix for register route
     *
     * @param string $prefix Annotation controller prefix
     * @param string $class Controller class name
     * @param string $controllerSuffix Controller suffix
     *
     * @return string
     */
    public static function getGroupPrefix(string $prefix, string $class, string $controllerSuffix): string
    {
        if (!$prefix = \trim($prefix)) {
            $regex  = '/^.*\\\(\w+)' . $controllerSuffix . '$/';

            if ($result = \preg_match($regex, $class, $match)) {
                $prefix = \lcfirst($match[1]);
            }
        }

        return $prefix;
    }

    /**
     * Returns true if STDOUT supports colorization.
     * This code has been copied and adapted from
     * \Symfony\Component\Console\Output\OutputStream.
     * @return boolean
     */
    public static function supportColor(): bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return
                '10.0.10586' === PHP_WINDOWS_VERSION_MAJOR . '.' . PHP_WINDOWS_VERSION_MINOR . '.' . PHP_WINDOWS_VERSION_BUILD ||
                // 0 == strpos(PHP_WINDOWS_VERSION_MAJOR . '.' . PHP_WINDOWS_VERSION_MINOR . PHP_WINDOWS_VERSION_BUILD, '10.') ||
                false !== getenv('ANSICON') ||
                'ON' === getenv('ConEmuANSI') ||
                'xterm' === getenv('TERM')// || 'cygwin' === getenv('TERM')
                ;
        }

        if (!\defined('STDOUT')) {
            return false;
        }

        return self::isInteractive(STDOUT);
    }

    /**
     * @return bool
     */
    public static function isSupport256Color(): bool
    {
        return DIRECTORY_SEPARATOR === '/' && strpos(getenv('TERM'), '256color') !== false;
    }

    /**
     * Returns if the file descriptor is an interactive terminal or not.
     * @param  int|resource $fileDescriptor
     * @return boolean
     */
    public static function isInteractive($fileDescriptor): bool
    {
        return \function_exists('posix_isatty') && @posix_isatty($fileDescriptor);
    }

}
