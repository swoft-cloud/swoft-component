<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Helper;

use function preg_replace;
use function rawurldecode;
use function rtrim;
use function strpos;

/**
 * Class RouteHelper
 *
 * @package Swoft\Http\Server\Helper
 */
class RouteHelper
{
    /**
     * check route path is static route
     *
     * @param string $route
     *
     * @return bool
     */
    public static function isStaticRoute(string $route): bool
    {
        return strpos($route, '{') === false && strpos($route, '[') === false;
    }

    /**
     * Format URI path
     *
     * @param string $path
     * @param bool   $ignoreLastSlash
     *
     * @return string
     */
    public static function formatPath(string $path, bool $ignoreLastSlash = true): string
    {
        if ($path === '/') {
            return '/';
        }

        // Clear '//', '///' => '/'
        if (false !== strpos($path, '//')) {
            $path = preg_replace('#\/\/+#', '/', $path);
        }

        // Must be start withs '/'
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }

        // Decode
        $path = rawurldecode($path);

        return $ignoreLastSlash ? rtrim($path, '/') : $path;
    }
}
