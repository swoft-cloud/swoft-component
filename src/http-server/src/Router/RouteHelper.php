<?php

namespace Swoft\Http\Server\Router;

/**
 * Class RouteHelper
 * @package Inhere\Route
 */
class RouteHelper
{
    /**
     * @param string $path
     * @param bool $ignoreLastSlash
     * @return string
     */
    public static function formatUriPath(string $path, bool $ignoreLastSlash = true): string
    {
        if ($path === '/') {
            return '/';
        }

        // clear '//', '///' => '/'
        if (false !== \strpos($path, '//')) {
            $path = (string)\preg_replace('#\/\/+#', '/', $path);
        }

        // decode
        $path = \rawurldecode($path);

        return $ignoreLastSlash ? \rtrim($path, '/') : $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function findFirstNode(string $path): string
    {
        // eg '/article/12' -> 'article'
        if ($pos = \strpos($path, '/', 1)) {
            return \substr($path, 1, $pos - 1);
        }

        return '';
    }

    /**
     * @param string $str
     * @return string
     */
    public static function str2Camel(string $str): string
    {
        $str = \trim($str, '-');

        // convert 'first-second' to 'firstSecond'
        if (\strpos($str, '-')) {
            $str = (string)\preg_replace_callback('/-+([a-z])/', function ($c) {
                return \strtoupper($c[1]);
            }, \trim($str, '- '));
        }

        return $str;
    }
}
